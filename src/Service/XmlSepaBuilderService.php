<?php

namespace App\Service;

use App\Entity\AbstractReceiptInvoiceLine;
use App\Entity\Receipt;
use App\Entity\Invoice;
use DateTimeInterface;
use Digitick\Sepa\TransferFile\Facade\CustomerDirectDebitFacade;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Digitick\Sepa\PaymentInformation;
use Digitick\Sepa\GroupHeader;
use Digitick\Sepa\Util\StringHelper;

class XmlSepaBuilderService
{
    public const DIRECT_DEBIT_PAIN_CODE = 'pain.008.001.02';
    public const DIRECT_DEBIT_LI_CODE = 'CORE';
    public const DEFAULT_REMITANCE_INFORMATION = 'Import mensual';

    private SpanishSepaHelperService $sshs;
    private string $bn;
    private string $bd;
    private string $ib;
    private string $bic;

    public function __construct(SpanishSepaHelperService $sshs, string $bn, string $bd, string $ib, string $bic)
    {
        $this->sshs = $sshs;
        $this->bn = $bn;
        $this->bd = $bd;
        $this->ib = $this->removeSpacesFrom($ib);
        $this->bic = $this->removeSpacesFrom($bic);
    }

    public function buildDirectDebitSingleReceiptXml(string $paymentId, DateTimeInterface $dueDate, Receipt $recepit): string
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        if ($recepit->isReadyToGenerateSepa()) {
            $this->addTransfer($directDebit, $paymentId, $recepit);
        }

        return $directDebit->asXML();
    }

    public function buildDirectDebitReceiptsXml(string $paymentId, DateTimeInterface $dueDate, $receipts): string
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        /** @var Receipt $receipt */
        foreach ($receipts as $receipt) {
            if ($receipt->isReadyToGenerateSepa() && !$receipt->getStudent()->getIsPaymentExempt()) {
                $this->addTransfer($directDebit, $paymentId, $receipt);
            }
        }

        return $directDebit->asXML();
    }

    public function buildDirectDebitSingleInvoiceXml(string $paymentId, DateTimeInterface $dueDate, Invoice $invoice): string
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        if ($invoice->isReadyToGenerateSepa()) {
            $this->addTransfer($directDebit, $paymentId, $invoice);
        }

        return $directDebit->asXML();
    }

    public function buildDirectDebitInvoicesXml(string $paymentId, DateTimeInterface $dueDate, $invoices): string
    {
        $directDebit = $this->buildDirectDebit($paymentId);
        $this->addPaymentInfo($directDebit, $paymentId, $dueDate);
        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            if ($invoice->isReadyToGenerateSepa() && !$invoice->getStudent()->getIsPaymentExempt()) {
                $this->addTransfer($directDebit, $paymentId, $invoice);
            }
        }

        return $directDebit->asXML();
    }

    private function buildDirectDebit(string $paymentId, bool $isTest = false): CustomerDirectDebitFacade
    {
        $msgId = 'MID'.StringHelper::sanitizeString($paymentId);
        $header = new GroupHeader($msgId, strtoupper(StringHelper::sanitizeString($this->bn)), $isTest);
        $header->setCreationDateTimeFormat('Y-m-d\TH:i:s');
        $header->setInitiatingPartyId($this->sshs->getSpanishCreditorIdFromNif($this->bd));

        return TransferFileFacadeFactory::createDirectDebitWithGroupHeader($header, self::DIRECT_DEBIT_PAIN_CODE);
    }

    private function addPaymentInfo(CustomerDirectDebitFacade $directDebit, string $paymentId, DateTimeInterface $dueDate): void
    {
        $directDebit->addPaymentInfo($paymentId, [
            'id' => StringHelper::sanitizeString($paymentId),
            'dueDate' => $dueDate,
            'creditorName' => strtoupper(StringHelper::sanitizeString($this->bn)),
            'creditorAccountIBAN' => $this->ib,
            'creditorAgentBIC' => $this->bic,
            'seqType' => PaymentInformation::S_ONEOFF,
            'creditorId' => $this->sshs->getSpanishCreditorIdFromNif($this->bd),
            'localInstrumentCode' => self::DIRECT_DEBIT_LI_CODE,
        ]);
    }

    private function addTransfer(CustomerDirectDebitFacade $directDebit, string $paymentId, $ari): void
    {
        $remitanceInformation = self::DEFAULT_REMITANCE_INFORMATION;
        if (count($ari->getLines()) > 0) {
            /** @var AbstractReceiptInvoiceLine $firstLine */
            $firstLine = $ari->getLines()[0];
            $remitanceInformation = $firstLine->getDescription();
        }

        $endToEndId = '';
        $amount = 0;
        if ($ari instanceof Receipt) {
            $endToEndId = $ari->getSluggedReceiptNumber();
            $amount = $ari->getBaseAmount();
        } elseif ($ari instanceof Invoice) {
            $endToEndId = $ari->getSluggedInvoiceNumber();
            $amount = $ari->getTotalAmount();
        }

        $transferInformation = [
            'amount' => $amount,
            'debtorIban' => $this->removeSpacesFrom($ari->getMainBank()->getAccountNumber()),
            'debtorName' => $ari->getMainEmailName(),
            'debtorMandate' => $ari->getDebtorMandate(),
            'debtorMandateSignDate' => $ari->getDebtorMandateSignDate(),
            'remittanceInformation' => $remitanceInformation,
            'endToEndId' => StringHelper::sanitizeString($endToEndId),
        ];

        if ($ari->getMainBank()->getSwiftCode()) {
            $transferInformation['debtorBic'] = $this->removeSpacesFrom($ari->getMainBank()->getSwiftCode());
        }

        $directDebit->addTransfer($paymentId, $transferInformation);
    }

    private function removeSpacesFrom(string $value): string
    {
        return str_replace(' ', '', $value);
    }
}
