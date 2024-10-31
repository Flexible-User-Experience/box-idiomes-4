<?php

namespace App\Message\Handler;

use App\Message\NewReceiptGroupCreatedMessage;
use App\Repository\ReceiptRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewReceiptGroupCreatedMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private ReceiptRepository $rr;

    public function __construct(LoggerInterface $logger, ReceiptRepository $rr)
    {
        $this->logger = $logger;
        $this->rr = $rr;
    }

    public function __invoke(NewReceiptGroupCreatedMessage $message)
    {
        $this->logger->info('[NRGCM] Group Receipt ID#'.$message->getReceiptGroupId().' succesfully delivered.');
        /*$receipt = $this->rr->find($message->getReceiptId());
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
                    $result = $this->ns->sendReceiptPdfNotification($receipt, $pdf);
                } else {
                    // send receipt reminder PDF
                    $result = $this->ns->sendReceiptReminderPdfNotification($receipt, $pdf);
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
        }*/
    }
}
