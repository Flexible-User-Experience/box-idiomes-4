<?php

namespace App\Message\Handler;

use App\Entity\BankCreditorSepa;
use App\Entity\ReceiptGroup;
use App\Message\NewReceiptGroupCreatedMessage;
use App\Repository\BankCreditorSepaRepository;
use App\Repository\ReceiptRepository;
use Digitick\Sepa\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewReceiptGroupCreatedMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    private ReceiptRepository $rr;
    private BankCreditorSepaRepository $bcsr;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, ReceiptRepository $rr, BankCreditorSepaRepository $bcsr)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->rr = $rr;
        $this->bcsr = $bcsr;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(NewReceiptGroupCreatedMessage $message)
    {
        $this->logger->info('[NRGCM] Message handler with '.count($message->getSelectedModelIdsArray()).' receipt Ids.');
        $banksCreditorSepa = $this->bcsr->getEnabledSortedByName();
        if (count($banksCreditorSepa) > 0 && count($message->getSelectedModelIdsArray()) > 0) {
            /** @var BankCreditorSepa $bankCreditorSepa */
            foreach ($banksCreditorSepa as $bankCreditorSepa) {
                $receiptGroup = new ReceiptGroup();
                $totalAmount = 0.0;
                foreach ($message->getSelectedModelIdsArray() as $receiptId) {
                    $receipt = $this->rr->find($receiptId);
                    if ($receipt && $receipt->getMainSubject()->getBankCreditorSepa() && $receipt->getMainSubject()->getBankCreditorSepa()->getId() === $bankCreditorSepa->getId()) {
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
                $receiptGroup
                    ->setBankCreditorSepa($bankCreditorSepa)
                    ->setBaseAmount($totalAmount)
                ;
                $this->em->persist($receiptGroup);
                $this->em->flush();
            }
        } else {
            $this->logger->error('[NRGCM] Empty selected models array OR enabled bank creditors array');
        }
    }
}
