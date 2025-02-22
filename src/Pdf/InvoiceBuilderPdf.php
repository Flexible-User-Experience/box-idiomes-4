<?php

namespace App\Pdf;

use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Enum\StudentPaymentEnum;
use TCPDF;

class InvoiceBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    public function build(Invoice $invoice): TCPDF
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs, $this->pb);
        $subject = $invoice->getMainSubject();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.invoice.invoice').' '.$invoice->getInvoiceNumber());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.invoice.invoice').' '.$invoice->getInvoiceNumber());
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

        // gaps
        $column2Gap = 114;
        $verticalTableGapSmall = 8;
        $verticalTableGap = 14;

        // invoice header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.invoice.pdf.invoice_data')), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.invoice.pdf.customer_data')), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_number').' '.$invoice->getInvoiceNumber(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $invoice->getMainSubject()->getFullName(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.invoice.pdf.invoice_date').' '.$invoice->getDateString(), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getDni(), '', false, 'L', true);

        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT + 4, $pdf->GetY() + 2);
        $pdf->Write(0, $this->bn, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getAddress(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->bd, '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $subject->getCity()->getCanonicalPostalString(), '', false, 'L', true);

        // svg globles
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // invoice table header
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(78, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.description'), false, 0, 'L');
        $pdf->Cell(15, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.units'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.priceUnit'), false, 0, 'R');
        $pdf->Cell(20, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.discount'), false, 0, 'R');
        $pdf->Cell(17, $verticalTableGap, $this->ts->trans('backend.admin.invoiceLine.total'), false, 1, 'R');
        $pdf->setFontStyle(null, '', 9);

        // invoice lines table rows
        /** @var InvoiceLine $line */
        foreach ($invoice->getLines() as $line) {
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $pdf->MultiCell(78, $verticalTableGapSmall, $line->getDescription(), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(15, $verticalTableGapSmall, $this->floatStringFormat($line->getUnits()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getPriceUnit()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(20, $verticalTableGapSmall, $this->floatStringFormat($line->getDiscount()), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatStringFormat($line->getTotal()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }
        $pdf->MultiCell(150, $verticalTableGapSmall, $this->ts->trans('Alumne').': '.$invoice->getStudent()->getFullName(), 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'M');

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // invoice table footer
        // base
        $pdf->MultiCell(133, $verticalTableGapSmall, $this->ts->trans('backend.admin.invoice.baseAmount'), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
        $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatMoneyFormat($invoice->calculateBaseAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        // iva tax
        if ($invoice->getTaxPercentage() > 0) {
            $pdf->MultiCell(133, $verticalTableGapSmall, '+'.$invoice->getTaxPercentage().'% IVA', 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatMoneyFormat($invoice->calculateTaxPercentage()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }
        // irpf
        if ($invoice->getIrpfPercentage() > 0) {
            $pdf->MultiCell(133, $verticalTableGapSmall, '-'.$invoice->getIrpfPercentage().'% IRPF', 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatMoneyFormat($invoice->calculateIrpfPercentatge()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }
        // total
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->MultiCell(133, $verticalTableGapSmall, strtoupper($this->ts->trans('backend.admin.invoiceLine.total')), 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
        $pdf->MultiCell(17, $verticalTableGapSmall, $this->floatMoneyFormat($invoice->getTotalAmount()), 0, 'R', 0, 1, '', '', true, 0, false, true, 0, 'M');
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
        } elseif (StudentPaymentEnum::CASH === $subject->getPayment()) {
            // cash
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.cash'), '', false, 'L', true);
        } elseif (StudentPaymentEnum::BANK_TRANSFER === $subject->getPayment()) {
            // bank transfer
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.payment.bank_transfer').' '.$this->ib, '', false, 'L', true);
        }

        // IVA exception
        if (0.0 === $invoice->getTaxPercentage()) {
            $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
            $pdf->setFontStyle(null, 'I', 9);
            $pdf->Write(7, $this->ts->trans('backend.admin.invoice.pdf.tax_excemption_legal_ad'), '', false, 'L', true);
        }

        return $pdf;
    }
}
