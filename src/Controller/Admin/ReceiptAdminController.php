<?php

namespace App\Controller\Admin;

use App\Controller\DefaultController;
use App\Entity\BankCreditorSepa;
use App\Entity\Invoice;
use App\Entity\Receipt;
use App\Enum\StudentPaymentEnum;
use App\Form\Model\GenerateReceiptModel;
use App\Form\Type\GenerateReceiptType;
use App\Form\Type\GenerateReceiptYearMonthChooserType;
use App\Manager\GenerateReceiptFormManager;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
use App\Service\NotificationService;
use App\Service\XmlSepaBuilderService;
use DateTime;
use Digitick\Sepa\Util\StringHelper;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use PhpZip\ZipFile;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReceiptAdminController.
 *
 * @category Controller
 */
class ReceiptAdminController extends BaseAdminController
{
    /**
     * Generate receipt action.
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException    If the object does not exist
     * @throws AccessDeniedException    If access is not granted
     * @throws NonUniqueResultException If problem with unique entities
     */
    public function generateAction(Request $request)
    {
        /** @var GenerateReceiptFormManager $grfm */
        $grfm = $this->container->get('app.generate_receipt_form_manager');

        // year & month chooser form
        $generateReceiptYearMonthChooser = new GenerateReceiptModel();
        /** @var Controller $this */
        $yearMonthForm = $this->createForm(GenerateReceiptYearMonthChooserType::class, $generateReceiptYearMonthChooser);
        $yearMonthForm->handleRequest($request);

        // build items form
        $generateReceipt = new GenerateReceiptModel();
        /** @var Controller $this */
        $form = $this->createForm(GenerateReceiptType::class, $generateReceipt);
        $form->handleRequest($request);

        if ($yearMonthForm->isSubmitted() && $yearMonthForm->isValid()) {
            $year = $generateReceiptYearMonthChooser->getYear();
            $month = $generateReceiptYearMonthChooser->getMonth();
            // build preview view
            $generateReceipt = $grfm->buildFullModelForm($year, $month);
            /** @var Controller $this */
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

    /**
     * Creator receipt action.
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function creatorAction(Request $request): RedirectResponse
    {
        /** @var TranslatorInterface $translator */
        $translator = $this->container->get('translator');
        /** @var GenerateReceiptFormManager $grfm */
        $grfm = $this->container->get('app.generate_receipt_form_manager');
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
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws Exception
     */
    public function createInvoiceAction(Request $request): RedirectResponse
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        /** @var Invoice $invoice */
        $invoice = $this->container->get('app.receipt_manager')->createInvoiceFromReceipt($object);

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($invoice);
        $em->flush();

        $this->addFlash('success', 'S\'ha generat la factura núm. '.$invoice->getInvoiceNumber());

        return $this->redirectToList();
    }

    /**
     * Generate PDF reminder receipt action.
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function reminderAction(Request $request): Response
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $object->getMainSubject()->getPayment()) {
            throw $this->createNotFoundException(sprintf('invalid payment type for object with id: %s', $id));
        }

        /** @var ReceiptBuilderPdf $rps */
        $rps = $this->container->get('app.receipt_reminder_pdf_builder');
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_reminder_'.$object->getSluggedReceiptNumber().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * Send PDF reminder receipt action.
     *
     * @throws NotFoundHttpException If the object does not exist
     */
    public function sendReminderAction(Request $request): RedirectResponse
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $object->getMainSubject()->getPayment()) {
            throw $this->createNotFoundException(sprintf('invalid payment type for object with id: %s', $id));
        }

        /** @var ReceiptBuilderPdf $rps */
        $rps = $this->container->get('app.receipt_reminder_pdf_builder');
        $pdf = $rps->build($object);

        /** @var NotificationService $messenger */
        $messenger = $this->container->get('app.notification');
        $result = $messenger->sendReceiptReminderPdfNotification($object, $pdf);

        if (0 === $result) {
            /* @var Controller $this */
            $this->addFlash('danger', 'S\'ha produït un error durant l\'enviament del recordatori de pagament del rebut núm. '.$object->getReceiptNumber().'. La persona '.$object->getMainEmailName().' no ha rebut cap missatge a la seva bústia.');
        } else {
            /* @var Controller $this */
            $this->addFlash('success', 'S\'ha enviat el recordatori de pagament del rebut núm. '.$object->getReceiptNumber().' amb PDF a la bústia '.$object->getMainEmail());
        }

        return $this->redirectToList();
    }

    /**
     * Generate PDF receipt action.
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function pdfAction(Request $request): Response
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        /** @var ReceiptBuilderPdf $rps */
        $rps = $this->container->get('app.receipt_pdf_builder');
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_'.$object->getSluggedReceiptNumber().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    /**
     * Send PDF receipt action.
     *
     * @throws NotFoundHttpException If the object does not exist
     */
    public function sendAction(Request $request): RedirectResponse
    {
        $request = $this->resolveRequest($request);
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
        $em = $this->container->get('doctrine')->getManager();
        $em->flush();

        /** @var ReceiptBuilderPdf $rps */
        $rps = $this->container->get('app.receipt_pdf_builder');
        $pdf = $rps->build($object);

        /** @var NotificationService $messenger */
        $messenger = $this->container->get('app.notification');
        $result = $messenger->sendReceiptPdfNotification($object, $pdf);

        if (0 === $result) {
            /* @var Controller $this */
            $this->addFlash('danger', 'S\'ha produït un error durant l\'enviament del rebut núm. '.$object->getReceiptNumber().'. La persona '.$object->getMainEmailName().' no ha rebut cap missatge a la seva bústia.');
        } else {
            /* @var Controller $this */
            $this->addFlash('success', 'S\'ha enviat el rebut núm. '.$object->getReceiptNumber().' amb PDF a la bústia '.$object->getMainEmail());
        }

        return $this->redirectToList();
    }

    /**
     * Generate SEPA direct debit XML action.
     *
     * @return Response|BinaryFileResponse
     */
    public function generateDirectDebitAction(Request $request): Response
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        /** @var XmlSepaBuilderService $xsbs */
        $xsbs = $this->container->get('app.xml_sepa_builder');
        $paymentUniqueId = uniqid('', false);
        $xml = $xsbs->buildDirectDebitSingleReceiptXml($paymentUniqueId, new DateTime('now + 3 days'), $object);

        $object
            ->setIsSepaXmlGenerated(true)
            ->setSepaXmlGeneratedDate(new DateTime())
        ;
        $em = $this->container->get('doctrine')->getManager();
        $em->flush();

        if (DefaultController::ENV_DEV === $this->getParameter('kernel.environment')) {
            return new Response($xml, 200, ['Content-type' => 'application/xml']);
        }

        $now = new DateTime();
        $fileSystem = new Filesystem();
        $fileNamePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'SEPA_receipt_'.$now->format('Y-m-d_H-i').'.xml';
        $fileSystem->touch($fileNamePath);
        $fileSystem->dumpFile($fileNamePath, $xml);

        $response = new BinaryFileResponse($fileNamePath, 200, ['Content-type' => 'application/xml']);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    /**
     * @return Response|RedirectResponse
     */
    public function batchActionGeneratereminderspdf(ProxyQueryInterface $selectedModelQuery)
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();

        try {
            /** @var ReceiptReminderBuilderPdf $rps */
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

            return new Response($pdf->Output('box_idiomes_receipt_reminders.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
        } catch (\Exception $e) {
            $this->addFlash('error', 'S\'ha produït un error al generar l\'arxiu de recordatoris de pagaments de rebut amb format PDF. Revisa els rebuts seleccionats.');
            $this->addFlash('error', $e->getMessage());

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters(),
                ])
            );
        }
    }

    /**
     * @return Response|RedirectResponse
     */
    public function batchActionGeneratesepaxmls(ProxyQueryInterface $selectedModelQuery)
    {
        $this->admin->checkAccess('edit');
        $em = $this->container->get('doctrine')->getManager();
        $selectedModels = $selectedModelQuery->execute();

        try {
            /** @var XmlSepaBuilderService $xsbs */
            $xsbs = $this->container->get('app.xml_sepa_builder');
            $paymentUniqueId = uniqid('', false);
            $xmlsArray = [];
            $banksCreditorSepa = $this->container->get('app.bank_creditor_sepa_repository')->getEnabledSortedByName();
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
            $now = new DateTime();
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
        } catch (\Exception $e) {
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
