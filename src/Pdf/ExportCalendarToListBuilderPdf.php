<?php

namespace App\Pdf;

use App\Entity\Event;
use App\Entity\Student;
use App\Model\ExportCalendarToList;
use App\Model\ExportCalendarToListDayItem;
use App\Service\SmartAssetsHelperService;
use DateTimeInterface;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Contracts\Translation\TranslatorInterface;
use TCPDF;

class ExportCalendarToListBuilderPdf
{
    private TCPDFController $tcpdf;
    private SmartAssetsHelperService $sahs;
    private TranslatorInterface $ts;
    private string $pwt;

    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, TranslatorInterface $ts, string $pwt)
    {
        $this->tcpdf = $tcpdf;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->pwt = $pwt;
    }

    public function build(ExportCalendarToList $calendarEventsList): TCPDF
    {
        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs);

        $maxCellWidth = BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.calendar.export.pdf.title'));
        $pdf->SetSubject($this->ts->trans('backend.admin.calendar.export.pdf.title'));
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_BOTTOM, BaseTcpdf::PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, BaseTcpdf::PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // Add start page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);

        $pdf->SetXY(BaseTcpdf::PDF_MARGIN_LEFT, BaseTcpdf::PDF_MARGIN_BOTTOM);
        $pdf->setFontStyle(null, 'B', 13);
        // description
        $pdf->Write(0, $this->ts->trans('backend.admin.calendar.export.pdf.title', [
            '%start%' => 'up',
            '%end%' => 'down',
        ]), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);

        /** @var ExportCalendarToListDayItem $day */
        foreach ($calendarEventsList->getDays() as $day) {
            $pdf->setFontStyle(null, 'B', 11);
            $pdf->Write(0, $day->getWeekdayName().' '.$this->asString($day->getDay()), '', false, 'L', true);
            $pdf->Write(0, ' ', '', false, 'L', true);
            /** @var Event $event */
            foreach ($day->getEvents() as $event) {
                $pdf->setFontStyle(null, 'B', 9);
                $pdf->Write(0, $event->getBegin()->format('H:i').'...'.$event->getEnd()->format('H:i').': '.$event->getClassroomString().' · '.$event->getGroup()->getName().' · '.$event->getGroup()->getBook().' · '.$event->getTeacher()->getName(), '', false, 'L', true);
                $pdf->Write(0, ' ', '', false, 'L', true);
                $pdf->setFontStyle(null, '', 9);
                /** @var Student $student */
                foreach ($event->getStudents() as $student) {
                    $pdf->Write(0, $student->getFullName(), '', false, 'L', true);
                }
                $pdf->Write(0, ' ', '', false, 'L', true);
            }
            $pdf->Write(0, ' ', '', false, 'L', true);
        }

        return $pdf;
    }

    private function asString(DateTimeInterface $date): string
    {
        return $date->format('d/m/Y');
    }
}
