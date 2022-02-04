<?php

namespace App\Controller\Admin;

use App\Entity\Receipt;
use App\Enum\StudentPaymentEnum;
use App\Form\Model\GenerateReceiptModel;
use App\Form\Type\GenerateReceiptType;
use App\Form\Type\GenerateReceiptYearMonthChooserType;
use App\Kernel;
use App\Manager\GenerateReceiptFormManager;
use App\Manager\ReceiptManager;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
use App\Service\NotificationService;
use App\Service\XmlSepaBuilderService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ReceiptAdminController extends CRUDController
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

    public function creatorAction(Request $request, TranslatorInterface $translator, GenerateReceiptFormManager $grfm): RedirectResponse
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

    public function createInvoiceAction(Request $request, EntityManagerInterface $em, ReceiptManager $rm): Response
    {
        $this->assertObjectExists($request, true);
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

    public function reminderAction(Request $request, ReceiptBuilderPdf $rps): Response
    {
        $this->assertObjectExists($request, true);
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

        return new Response($pdf->Output('box_idiomes_receipt_reminder_'.$object->getSluggedReceiptNumber().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    public function sendReminderAction(Request $request, ReceiptBuilderPdf $rps, NotificationService $messenger): RedirectResponse
    {
        $this->assertObjectExists($request, true);
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

    public function pdfAction(Request $request, ReceiptBuilderPdf $rps): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $pdf = $rps->build($object);

        return new Response($pdf->Output('box_idiomes_receipt_'.$object->getSluggedReceiptNumber().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    public function sendAction(Request $request, EntityManagerInterface $em, ReceiptBuilderPdf $rps, NotificationService $messenger): RedirectResponse
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $object
            ->setIsSended(true)
            ->setSendDate(new DateTimeImmutable())
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

    public function generateDirectDebitAction(Request $request, EntityManagerInterface $em, XmlSepaBuilderService $xsbs): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Receipt $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $paymentUniqueId = uniqid('', true);
        $xml = $xsbs->buildDirectDebitSingleReceiptXml($paymentUniqueId, new DateTime('now + 3 days'), $object);
        $object
            ->setIsSepaXmlGenerated(true)
            ->setSepaXmlGeneratedDate(new DateTimeImmutable())
        ;
        $em->flush();
        if (Kernel::ENV_DEV === $this->getParameter('kernel.environment')) {
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

    public function batchActionGeneratereminderspdf(ProxyQueryInterface $selectedModelQuery, ReceiptReminderBuilderPdf $rrps): Response
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();
        try {
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

    public function batchActionGeneratefirstsepaxmls(ProxyQueryInterface $selectedModelQuery, EntityManagerInterface $em, XmlSepaBuilderService $xsbs): Response
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();
        try {
            $paymentUniqueId = uniqid('', true);
            $xmls = $xsbs->buildDirectDebitReceiptsXml($paymentUniqueId, new DateTime('now + 3 days'), $selectedModels);
            /** @var Receipt $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->isReadyToGenerateSepa() && !$selectedModel->getStudent()->getIsPaymentExempt()) {
                    $selectedModel
                        ->setIsSepaXmlGenerated(true)
                        ->setSepaXmlGeneratedDate(new DateTimeImmutable())
                    ;
                }
            }
            $em->flush();
            if (Kernel::ENV_DEV === $this->getParameter('kernel.environment')) {
                return new Response($xmls, 200, ['Content-type' => 'application/xml']);
            }
            $now = new DateTimeImmutable();
            $fileSystem = new Filesystem();
            $fileNamePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'SEPA_FRST_receipts_'.$now->format('Y-m-d_H-i').'.xml';
            $fileSystem->touch($fileNamePath);
            $fileSystem->dumpFile($fileNamePath, $xmls);
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

    public function batchActionGeneratesepaxmls(ProxyQueryInterface $selectedModelQuery, EntityManagerInterface $em, XmlSepaBuilderService $xsbs): Response
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();
        try {
            $paymentUniqueId = uniqid('', true);
            $xmls = $xsbs->buildDirectDebitReceiptsXml($paymentUniqueId, new DateTime('now + 3 days'), $selectedModels);
            /** @var Receipt $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                if ($selectedModel->isReadyToGenerateSepa() && !$selectedModel->getStudent()->getIsPaymentExempt()) {
                    $selectedModel
                        ->setIsSepaXmlGenerated(true)
                        ->setSepaXmlGeneratedDate(new DateTimeImmutable())
                    ;
                }
            }
            $em->flush();
            if (Kernel::ENV_DEV === $this->getParameter('kernel.environment')) {
                return new Response($xmls, 200, ['Content-type' => 'application/xml']);
            }
            $now = new DateTimeImmutable();
            $fileSystem = new Filesystem();
            $fileNamePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'SEPA_RCUR_receipts_'.$now->format('Y-m-d_H-i').'.xml';
            $fileSystem->touch($fileNamePath);
            $fileSystem->dumpFile($fileNamePath, $xmls);
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

    public function batchActionMarkaspayed(ProxyQueryInterface $selectedModelQuery, Request $request): RedirectResponse
    {
        $this->admin->checkAccess('edit');
        $selectedModels = $selectedModelQuery->execute();
        try {
            /** @var Receipt $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                $selectedModel
                    ->setIsPayed(true)
                    ->setPaymentDate(new DateTimeImmutable())
                ;
            }
            $modelManager = $this->admin->getModelManager();
            $modelManager->update($selectedModel);
            $this->addFlash('success', 'S\'han marcat '.count($selectedModels).' rebuts com a pagats correctament.');
        } catch (Exception $e) {
            $this->addFlash('error', 'S\'ha produït un error al generar marcar els rebuts com a pagats. Revisa els rebuts seleccionats.');
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToList();
    }
}
