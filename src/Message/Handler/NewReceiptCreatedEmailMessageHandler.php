<?php

namespace App\Message\Handler;

use App\Enum\StudentPaymentEnum;
use App\Message\NewReceiptCreatedEmailMessage;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
use App\Repository\ReceiptRepository;
use App\Service\NotificationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewReceiptCreatedEmailMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private ReceiptRepository $rr;
    private ReceiptReminderBuilderPdf $rrbp;
    private ReceiptBuilderPdf $rbp;
    private NotificationService $messenger;

    public function __construct(LoggerInterface $logger, ReceiptRepository $rr, ReceiptReminderBuilderPdf $rrbp, ReceiptBuilderPdf $rbp, NotificationService $messenger)
    {
        $this->logger = $logger;
        $this->rr = $rr;
        $this->rrbp = $rrbp;
        $this->rbp = $rbp;
        $this->messenger = $messenger;
    }

    public function __invoke(NewReceiptCreatedEmailMessage $message)
    {
        $receipt = $this->rr->find($message->getReceiptId());
        if ($receipt) {
            if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $receipt->getMainSubject()->getPayment()) {
                // build receipt PDF
                $pdf = $this->rbp->build($receipt);
            } else {
                // build receipt reminder PDF
                $pdf = $this->rrbp->build($receipt);
            }
            $this->logger->info('[DRBBEC] PDF receipt ID#'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully build.');
            if ($receipt->getMainEmail()) {
                if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $receipt->getMainSubject()->getPayment()) {
                    // send receipt PDF
                    $result = $this->messenger->sendReceiptPdfNotification($receipt, $pdf);
                } else {
                    // send receipt reminder PDF
                    $result = $this->messenger->sendReceiptReminderPdfNotification($receipt, $pdf);
                }
                if (0 === $result) {
                    $this->logger->error('[DRBBEC] delivering PDF receipt ID#'.$receipt->getId().' number '.$receipt->getReceiptNumber().' failed.');
                } else {
                    $this->logger->info('[DRBBEC] PDF receipt ID#'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully delivered.');
                }
            } else {
                $this->logger->error('[DRBBEC] PDF receipt ID#'.$receipt->getId().' number '.$receipt->getReceiptNumber().' not delivered. Missing email in '.$receipt->getMainSubject()->getFullCanonicalName().'.');
            }
        } else {
            $this->logger->error('[DRBBEC] Receipt ID#'.$message->getReceiptId().' NOT found');
        }
    }
}
