<?php

namespace App\Pdf;

use App\Entity\Receipt;
use App\Entity\ReceiptLine;
use App\Enum\StudentPaymentEnum;
use App\Service\SmartAssetsHelperService;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use TCPDF;

class ReceiptBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, Translator $ts, string $pwt, string $bn, string $bd, string $ba, string $bc, string $ib, string $locale)
    {
        parent::__construct($tcpdf, $sahs, $ts, $pwt, $bn, $bd, $ba, $bc, $ib, $locale);
    }

    public function build(Receipt $receipt): TCPDF
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs);
        $subject = $receipt->getMainSubject();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.receipt.receipt').' '.$receipt->getReceiptNumber());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.receipt.receipt').' '.$receipt->getReceiptNumber());
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // remove default header/footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP, BaseTcpdf::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, BaseTcpdf::PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // Add start page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_TOP);

        // invoice header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $column2Gap = 114;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.receipt.pdf.receipt_data_head')), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.invoice.pdf.customer_data')), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_number').' '.$receipt->getReceiptNumber(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getFullName(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.receipt.pdf.receipt_date').' '.$receipt->getDateString(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getDni(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('Alumne').': '.$receipt->getStudent()->getFullName(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getAddress(), '', false, 'L', true);

        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getCity()->getCanonicalPostalString(), '', false, 'L', true);

        // svg globles
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/build/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getAbsoluteAssetFilePath('/build/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // receipt table header
        $verticalTableGapSmall = 8;
        $verticalTableGap = 14;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(80, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.description'), false, 0, 'L');
        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.units'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.priceUnit'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.discount'), false, 0, 'R');
        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.total'), false, 1, 'R');
        $pdf->setFontStyle(null, '', 9);

        // receipt lines table rows
        /** @var ReceiptLine $line */
        foreach ($receipt->getLines() as $line) {
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $pdf->MultiCell(80, $verticalTableGapSmall, $line->getDescription(), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->getUnits()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getPriceUnit()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getDiscount()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->calculateBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        $pdf->setFontStyle(null, 'B', 9);
        $pdf->MultiCell(135, $verticalTableGapSmall, strtoupper($this->ts->trans('backend.admin.invoiceLine.total')), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
        $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatMoneyFormat($receipt->getBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        $pdf->setFontStyle(null, '', 9);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL + 1);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG + $verticalTableGapSmall);

        // payment method
        $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment_type').' '.strtoupper($this->ts->trans(StudentPaymentEnum::getReversedEnumArray()[$subject->getPayment()])), '', false, 'L', true);
        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $subject->getPayment()) {
            // SEPA direct debit
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.account_number').' '.$subject->getBank()->getAccountNumber(), '', false, 'L', true);
        } elseif (StudentPaymentEnum::BANK_TRANSFER === $subject->getPayment()) {
            // bank transfer
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.bank_transfer').' '.$this->ib, '', false, 'L', true);
        }

        return $pdf;
    }
}
