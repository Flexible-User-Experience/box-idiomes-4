<?php

namespace App\Controller\Admin;

use App\Controller\DefaultController;
use App\Entity\BankCreditorSepa;
use App\Entity\Receipt;
use App\Enum\StudentPaymentEnum;
use App\Form\Model\GenerateReceiptModel;
use App\Form\Type\GenerateReceiptType;
use App\Form\Type\GenerateReceiptYearMonthChooserType;
use App\Manager\GenerateReceiptFormManager;
use App\Manager\ReceiptManager;
use App\Pdf\ReceiptBuilderPdf;
use App\Service\NotificationService;
use App\Service\XmlSepaBuilderService;
use DateTime;
use DateTimeImmutable;
use Digitick\Sepa\Util\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpZip\ZipFile;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReceiptAdminController extends BaseAdminController
{
    public function generateAction(Request $request, GenerateReceiptFormManager $grfm): Response
    {
        // year & month chooser form
        $generateReceiptYearMonthChooser = new GenerateReceiptModel();
        $yearMonthForm = $this->createForm(GenerateReceiptYearMonthChooserType::class, $generateReceiptYearMonthChooser);
        $yearMonthForm->handleRequest($request);
        // build items form
        $generateReceipt = new GenerateReceiptModel();
        $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        $form->handleRequest($request);
        if ($yearMonthForm->isSubmitted() && $yearMonthForm->isValid()) {
            $year = $generateReceiptYearMonthChooser->getYear();
            $month = $generateReceiptYearMonthChooser->getMonth();
            // build preview view
            $generateReceipt = $grfm->buildFullModelForm($year, $month);
            $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        }

        return $this->renderWithExtraParams(
            'Admin/Receipt/generate_receipt_form.html.twig',
            [
                'action' => 'generate',
                'year_month_form' => $yearMonthForm->createView(),
                'form' => $form->createView(),
            ]
        );
    }

    public function creatorAction(Request $request, GenerateReceiptFormManager $grfm, TranslatorInterface $translator): RedirectResponse
    {
        $generateReceipt = $grfm->transformRequestArrayToModel($request->get('generate_receipt'));
        if (array_key_exists('generate_and_send', $request->get(GenerateReceiptType::NAME))) {
            // generate receipts and send it by email
            $recordsParsed = $grfm->persistAndDeliverFullModelForm($generateReceipt);
        } else {
            // only generate receipts
            $recordsParsed = $grfm->persistFullModelForm($generateReceipt);
        }
        if (0 === $recordsParsed) {
            $this->addFlash('danger', $translator->trans('backend.admin.receipt.generator.no_records_presisted'));
        } else {
            $this->addFlash('success', $translator->trans('backend.admin.receipt.generator.flash_success', ['%amount%' => $recordsParsed], 'messages'));
        }

        return $this->redirectToList();
    }

    /**
     * Create an Invoice from a Receipt action.
     */
    public function createInvoiceAction(Request $request, EntityManagerInterface $em, ReceiptManager $rm): RedirectResponse
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $invoice = $rm->createInvoiceFromReceipt($object);
        $em->persist($invoice);
        $em->flush();
        $this->addFlash('success', 'S\'ha generat la factura núm. '.$invoice->getInvoiceNumber());

        return $this->redirectToList();
    }

    /**
     * Generate PDF reminder receipt action.
     */
    public function reminderAction(Request $request, ReceiptBuilderPdf $rps): Response
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $object->getMainSubject()->getPayment()) {
            throw $this->createNotFoundException(sprintf('invalid payment type for object with id: %s', $id));
        }
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_reminder_'.$object->getSluggedReceiptNumber().'.pdf'), 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * Send PDF reminder receipt action.
     */
    public function sendReminderAction(Request $request, ReceiptBuilderPdf $rps, NotificationService $messenger): RedirectResponse
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $object->getMainSubject()->getPayment()) {
            throw $this->createNotFoundException(sprintf('invalid payment type for object with id: %s', $id));
        }
        $pdf = $rps->build($object);
        $result = $messenger->sendReceiptReminderPdfNotification($object, $pdf);
        if (0 === $result) {
            $this->addFlash('danger', 'S\'ha produït un error durant l\'enviament del recordatori de pagament del rebut núm. '.$object->getReceiptNumber().'. La persona '.$object->getMainEmailName().' no ha rebut cap missatge a la seva bústia.');
        } else {
            $this->addFlash('success', 'S\'ha enviat el recordatori de pagament del rebut núm. '.$object->getReceiptNumber().' amb PDF a la bústia '.$object->getMainEmail());
        }

        return $this->redirectToList();
    }

    /**
     * Generate PDF receipt action.
     */
    public function pdfAction(Request $request, ReceiptBuilderPdf $rps): Response
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_'.$object->getSluggedReceiptNumber().'.pdf'), 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * Send PDF receipt action.
     */
    public function sendAction(Request $request, EntityManagerInterface $em, ReceiptBuilderPdf $rps, NotificationService $messenger): RedirectResponse
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $object
            ->setIsSended(true)
            ->setSendDate(new DateTime())
        ;
        $em->flush();
        $pdf = $rps->build($object);
        $result = $messenger->sendReceiptPdfNotification($object, $pdf);

        if (0 === $result) {
            $this->addFlash('danger', 'S\'ha produït un error durant l\'enviament del rebut núm. '.$object->getReceiptNumber().'. La persona '.$object->getMainEmailName().' no ha rebut cap missatge a la seva bústia.');
        } else {
            $this->addFlash('success', 'S\'ha enviat el rebut núm. '.$object->getReceiptNumber().' amb PDF a la bústia '.$object->getMainEmail());
        }

        return $this->redirectToList();
    }

    /**
     * Generate SEPA direct debit XML action.
     */
    public function generateDirectDebitAction(Request $request, EntityManagerInterface $em, XmlSepaBuilderService $xsbs): Response
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $paymentUniqueId = uniqid('', false);
        $xml = $xsbs->buildDirectDebitSingleReceiptXml($paymentUniqueId, new DateTime('now + 3 days'), $object);
        $object
            ->setIsSepaXmlGenerated(true)
            ->setSepaXmlGeneratedDate(new DateTime())
        ;
        $em->flush();
        if (DefaultController::ENV_DEV === $this->getParameter('kernel.environment')) {
            return new Response($xml, 200, ['Content-type' => 'application/xml']);
        }
        $now = new DateTimeImmutable();
        $fileSystem = new Filesystem();
        $fileNamePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'SEPA_receipt_'.$now->format('Y-m-d_H-i').'.xml';
        $fileSystem->touch($fileNamePath);
        $fileSystem->dumpFile($fileNamePath, $xml);

        $response = new BinaryFileResponse($fileNamePath, 200, ['Content-type' => 'application/xml']);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    public function batchActionGeneratereminderspdf(ProxyQueryInterface $selectedModelQuery): Response
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();

        try {
            $rrps = $this->container->get('app.receipt_reminder_pdf_builder');
            $pdf = $rrps->buildBatchReminder();
            /** @var Receipt $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER !== $selectedModel->getMainSubject()->getPayment() && !$selectedModel->getStudent()->getIsPaymentExempt()) {
                    // add page
                    $pdf->AddPage('L', 'A5', true, true);
                    $rrps->buildReceiptRemainderPageForItem($pdf, $selectedModel);
                }
            }

            return new Response($pdf->Output('box_idiomes_receipt_reminders.pdf'), 200, ['Content-type' => 'application/pdf']);
        } catch (Exception $e) {
            $this->addFlash('error', 'S\'ha produït un error al generar l\'arxiu de recordatoris de pagaments de rebut amb format PDF. Revisa els rebuts seleccionats.');
            $this->addFlash('error', $e->getMessage());

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters(),
                ])
            );
        }
    }

    public function batchActionGeneratesepaxmls(ProxyQueryInterface $selectedModelQuery): Response
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $xsbs = $this->container->get('app.xml_sepa_builder');
        $bcsr = $this->container->get('app.bank_creditor_sepa_repository');
        try {
            $paymentUniqueId = uniqid('', false);
            $xmlsArray = [];
            $banksCreditorSepa = $bcsr->getEnabledSortedByName();
            /** @var BankCreditorSepa $bankCreditorSepa */
            foreach ($banksCreditorSepa as $bankCreditorSepa) {
                $xmlsArray[] = $xsbs->buildDirectDebitReceiptsXmlForBankCreditorSepa($paymentUniqueId, new DateTime('now + 3 days'), $selectedModels, $bankCreditorSepa);
            }
            /** @var Receipt $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->isReadyToGenerateSepa() && !$selectedModel->getStudent()->getIsPaymentExempt()) {
                    $selectedModel
                        ->setIsSepaXmlGenerated(true)
                        ->setSepaXmlGeneratedDate(new DateTime())
                    ;
                }
            }
            $em->flush();
            $now = new DateTimeImmutable();
            $fileName = 'SEPA_receipts_'.$now->format('Y-m-d_H-i').'.zip';
            $fileNamePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.$fileName;
            $zipFile = new ZipFile();
            $index = 0;
            /** @var BankCreditorSepa $bankCreditorSepa */
            foreach ($banksCreditorSepa as $bankCreditorSepa) {
                $zipFile->addFromString('SEPA_'.StringHelper::sanitizeString($bankCreditorSepa->getName()).'.xml', $xmlsArray[$index]);
                ++$index;
            }
            $zipFile->saveAsFile($fileNamePath)->close();
            $response = new BinaryFileResponse($fileNamePath, 200, ['Content-type' => 'application/xml']);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

            return $response;
        } catch (Exception $e) {
            $this->addFlash('error', 'S\'ha produït un error al generar l\'arxiu SEPA amb format XML. Revisa els rebuts seleccionats.');
            $this->addFlash('error', $e->getMessage());

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters(),
                ])
            );
        }
    }
}
