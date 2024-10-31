<?php

namespace App\Message\Handler;

use App\Entity\ReceiptGroup;
use App\Message\NewReceiptGroupCreatedMessage;
use App\Repository\ReceiptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewReceiptGroupCreatedMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    private ReceiptRepository $rr;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, ReceiptRepository $rr)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->rr = $rr;
    }

    public function __invoke(NewReceiptGroupCreatedMessage $message)
    {
        $this->logger->info('[NRGCM] Message handler with '.count($message->getSelectedModelIdsArray()).' receipt Ids.');
        if (count($message->getSelectedModelIdsArray()) > 0) {
            $receiptGroup = new ReceiptGroup();
            $totalAmount = 0.0;
            foreach ($message->getSelectedModelIdsArray() as $receiptId) {
                $receipt = $this->rr->find($receiptId);
                if ($receipt) {
                    $receiptGroup->addReceipt($receipt);
                    $receiptGroup
                        ->setYear($receipt->getYear())
                        ->setMonth($receipt->getMonth())
                        ->setTrainingCenter($receipt->getTrainingCenter())
                    ;
                    $totalAmount += $receipt->getBaseAmount();
                } else {
                    $this->logger->error('[NRGCM] Receipt ID#'.$receiptId.' NOT found');
                }
            }
            $receiptGroup->setBaseAmount($totalAmount);
            $this->em->persist($receiptGroup);
            $this->em->flush();
        } else {
            $this->logger->error('[NRGCM] Empty selected models array');
        }
    }
}
