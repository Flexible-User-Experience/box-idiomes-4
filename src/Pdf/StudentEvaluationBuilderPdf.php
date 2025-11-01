<?php

namespace App\Pdf;

use App\Entity\AbstractBase;
use App\Entity\StudentEvaluation;
use App\Enum\StudentEvaluationEnum;

class StudentEvaluationBuilderPdf extends AbstractReceiptInvoiceBuilderPdf
{
    public function build(StudentEvaluation $studentEvaluation): \TCPDF
    {
        if ($this->sahs->isCliContext()) {
            $this->ts->setLocale($this->locale);
        }

        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs, $this->pb);
        $student = $studentEvaluation->getStudent();

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.student_evaluation.pdf.title').' '.$student->getFullName());
        $pdf->SetSubject($this->ts->trans('backend.admin.student_evaluation.pdf.subject').' '.$studentEvaluation->getFullCourseAsString());
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
        $verticalTableGapSmall = 8;
        $verticalTableGap = 14;

        // evaluation header
        $retainedYForGlobes = $pdf->GetY() - 4;
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.student_evaluation.pdf.evaluation_data')), '', false, 'L');
        $pdf->SetX($column2Gap);
        $pdf->Write(0, strtoupper($this->ts->trans('backend.admin.student_evaluation.pdf.student_data')), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, '', 9);

        // left column
        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.student_evaluation.course').' '.$studentEvaluation->getFullCourseAsString(), '', false, 'L');
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $student->getFullName(), '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.student_evaluation.evaluation').' '.$this->getEvaluationString($studentEvaluation->getEvaluation()), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        $pdf->Write(0, $student->getDni() ?: '-', '', false, 'L', true);

        $pdf->SetX(BaseTcpdf::PDF_MARGIN_LEFT + 4);
        $pdf->Write(0, $this->ts->trans('backend.admin.student_evaluation.pdf.evaluation_date').' '.new \DateTimeImmutable()->format(AbstractBase::DATE_STRING_FORMAT), '', false, 'L', false);
        $pdf->SetX($column2Gap);
        if ($student->getParent()) {
            $pdf->Write(0, $this->ts->trans('backend.admin.student.parent').': '.$student->getParent()->getFullName(), '', false, 'L', true);
        } else {
            $pdf->Write(0, '', '', false, 'L', true);
        }

        // svg globes
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-violet.svg'), BaseTcpdf::PDF_MARGIN_LEFT, $retainedYForGlobes, 70, 35);
        $pdf->drawSvg($this->sahs->getLocalAssetsPath('/svg/globe-blue.svg'), BaseTcpdf::PDF_MARGIN_LEFT + 80, $retainedYForGlobes, 70, 35);

        // horizontal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG * 3);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // evaluation table header
        $pdf->setFontStyle(null, 'B', 10);
        $pdf->Cell(150, $verticalTableGap, $this->ts->trans('backend.admin.student_evaluation.pdf.skills_evaluation'), false, 1, 'C');
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->Cell(75, $verticalTableGap, $this->ts->trans('backend.admin.student_evaluation.pdf.skill'), false, 0, 'L');
        $pdf->Cell(75, $verticalTableGap, $this->ts->trans('backend.admin.student_evaluation.pdf.mark'), false, 1, 'C');
        $pdf->setFontStyle(null, '', 9);

        // evaluation skills rows
        $skills = [
            ['writting', $studentEvaluation->getWritting()],
            ['reading', $studentEvaluation->getReading()],
            ['use_of_english', $studentEvaluation->getUseOfEnglish()],
            ['listening', $studentEvaluation->getListening()],
            ['speaking', $studentEvaluation->getSpeaking()],
            ['behaviour', $studentEvaluation->getBehaviour()],
        ];

        foreach ($skills as $skill) {
            $pdf->MultiCell(75, $verticalTableGapSmall, $this->ts->trans('backend.admin.student_evaluation.'.$skill[0]), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
            $pdf->MultiCell(75, $verticalTableGapSmall, $skill[1] ?: '-', 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');
        }

        // global mark
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->setFontStyle(null, 'B', 9);
        $pdf->MultiCell(75, $verticalTableGapSmall, $this->ts->trans('backend.admin.student_evaluation.global_mark'), 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'M');
        $pdf->MultiCell(75, $verticalTableGapSmall, $studentEvaluation->getGlobalMark() ?: '-', 0, 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');
        $pdf->setFontStyle(null, '', 9);

        // horizontal divider
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->drawInvoiceLineSeparator($pdf->GetY());
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        // comments section
        if ($studentEvaluation->getComments()) {
            $pdf->setFontStyle(null, 'B', 9);
            $pdf->Write(0, $this->ts->trans('backend.admin.student_evaluation.comments'), '', false, 'L', true);
            $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
            $pdf->setFontStyle(null, '', 9);
            $pdf->MultiCell(150, 0, $studentEvaluation->getComments(), 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'T');
            $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        }

        // notification information
        if ($studentEvaluation->hasBeenNotified()) {
            $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
            $pdf->setFontStyle(null, 'I', 8);
            $pdf->Write(0, $this->ts->trans('backend.admin.student_evaluation.pdf.notification_info', [
                '%date%' => $studentEvaluation->getNotificationDateString(),
            ]), '', false, 'L', true);
        }

        return $pdf;
    }

    private function getEvaluationString(int $evaluation): string
    {
        return match ($evaluation) {
            StudentEvaluationEnum::FIRST_TRIMESTER => $this->ts->trans('backend.admin.student_evaluation.first_trimester'),
            StudentEvaluationEnum::SECOND_TRIMESTER => $this->ts->trans('backend.admin.student_evaluation.second_trimester'),
            StudentEvaluationEnum::THRID_TRIMESTER => $this->ts->trans('backend.admin.student_evaluation.third_trimester'),
            default => (string) $evaluation,
        };
    }
}
