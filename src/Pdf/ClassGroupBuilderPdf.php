<?php

namespace App\Pdf;

use App\Entity\AbstractBase;
use App\Entity\ClassGroup;
use App\Entity\Student;

class ClassGroupBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    /**
     * @throws \ReflectionException
     */
    public function build(ClassGroup $classGroup, $students): \TCPDF
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs, $this->pb);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.invoice.invoice').' '.$classGroup->getId());
        $pdf->SetSubject($this->ts->trans('backend.admin.invoice.detail').' '.$this->ts->trans('backend.admin.invoice.invoice').' '.$classGroup->getId());
        // set default font subsetting mode
        $pdf->setFontSubsetting();
        // remove default header/footer
        $pdf->setPrintHeader();
        $pdf->setPrintFooter();
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
        $verticalTableGap = 10;

        // today
        $today = new \DateTimeImmutable();

        // invoice header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.class_group.pdf.title')), '', false, 'L');
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $today->format(AbstractBase::DATE_STRING_FORMAT).'    ', '', false, 'R', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        // left header
        $lockedY = $pdf->GetY();
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(6, $this->ts->trans('backend.admin.class_group.pdf.group').' '.$classGroup->getCode(), '', false, 'L', true);
        if ($classGroup->getName()) {
            $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
            $pdf->Write(6, $this->ts->trans('backend.admin.class_group.name').' '.$classGroup->getName(), '', false, 'L', true);
        }
        if ($classGroup->getBook()) {
            $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
            $pdf->Write(6, $this->ts->trans('backend.admin.class_group.book').' '.$classGroup->getBook(), '', false, 'L', true);
        }

        // right header
        $pdf->SetXY($column2Gap, $lockedY);
        $pdf->Write(6, $this->ts->trans('backend.admin.class_group.pdf.total').' '.count($students).'    ', '', false, 'R', true);
        $pdf->SetX($column2Gap);
        $pdf->Write(6, ($classGroup->isForPrivateLessons() ? $this->ts->trans('backend.admin.is_for_private_lessons') : $this->ts->trans('backend.admin.is_not_for_private_lessons')).'    ', '', false, 'R', true);
        $pdf->SetX($column2Gap);
        $pdf->RoundedRect($pdf->GetX(), $pdf->GetY() + 2, 61.5, 3, 1, '1111', 'F', [], $this->hex2RGBarray($classGroup->getColor()));

        // svg globles
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        if (0 < count($students)) {
            $pdf->SetLineStyle([
                'width' => 0.15,
                'cap' => 'butt',
                'join' => 'miter',
                'dash' => 0,
                'color' => [0, 0, 0],
            ]);
            // students table header
            $pdf->setCellPaddings(0.5, 1.5, 0.5, 1.5);
            $pdf->setFontStyle(null, 'B', 9);
            $pdf->Cell(78, 0, $this->ts->trans('backend.admin.student.name'), 'T', 0, 'L');
            $pdf->Cell(72, 0, $this->ts->trans('backend.admin.student.email'), 'T', 1, 'R');
            $pdf->setFontStyle(null, '', 9);
            // students lines table rows
            /** @var Student $student */
            foreach ($students as $student) {
                $pdf->Cell(78, 0, $student->getFullCanonicalName(), 'T', 0, 'L');
                $pdf->Cell(72, 0, $student->getEmail(), 'T', 1, 'R');
            }
        } else {
            $pdf->Cell(150, $verticalTableGap, $this->ts->trans('backend.admin.class_group.emails_generator.flash_warning'), false, 1, 'L');
        }

        // final horitzonal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        return $pdf;
    }
}
