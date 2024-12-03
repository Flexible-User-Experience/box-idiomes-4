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
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NewReceiptGroupCreatedMessageHandler
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
                $found = false;
                $receiptGroup = new ReceiptGroup();
                $totalAmount = 0.0;
                foreach ($message->getSelectedModelIdsArray() as $receiptId) {
                    $receipt = $this->rr->find($receiptId);
                    if ($receipt && $receipt->getMainSubject()->getBankCreditorSepa() && $receipt->getMainSubject()->getBankCreditorSepa()->getId() === $bankCreditorSepa->getId()) {
                        $found = true;
                        $receiptGroup->addReceipt($receipt);
                        // BE CAREFUL: following code can be tricky...
                        // take in mind that maybe not be consistent due to full group can not have same year, month or even training center
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
                if ($found) {
                    $this->em->persist($receiptGroup);
                    $this->em->flush();
                }
            }
        } else {
            $this->logger->error('[NRGCM] Empty selected models array OR enabled bank creditors array');
        }
    }
}
