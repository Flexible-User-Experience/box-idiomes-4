<?php

namespace App\Pdf;

use App\Entity\Event;
use App\Model\ExportCalendarToList;
use App\Model\ExportCalendarToListDayHourItem;
use App\Model\ExportCalendarToListDayItem;
use App\Service\SmartAssetsHelperService;
use DateTimeInterface;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Contracts\Translation\TranslatorInterface;
use TCPDF;

class ExportCalendarToListBuilderPdf
{
    private const FIRST_CELL_WIDTH = 20;
    private const CELL_WIDTH = 34;

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

        $correctorMargin = 20;
        $leftMargin = BaseTcpdf::PDF_MARGIN_LEFT - $correctorMargin;
        $rightMargin = BaseTcpdf::PDF_MARGIN_RIGHT - $correctorMargin;
        $maxCellWidth = BaseTcpdf::PDF_WIDTH - $leftMargin - $rightMargin;

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
        $pdf->SetMargins($leftMargin, 10, $rightMargin);
        $pdf->SetAutoPageBreak(true, 10);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // make page
        $this->buildNewPage($pdf, $leftMargin, $rightMargin);
        $pdf->SetLineWidth(0.1);
        $iteratorDayIndex = 0;
        $daysAmount = count($calendarEventsList->getDays());
        /** @var ExportCalendarToListDayItem $day */
        foreach ($calendarEventsList->getDays() as $day) {
            // don't draw sundays
            ++$iteratorDayIndex;
            if ('0' !== $day->getDay()->format('w')) {
                $pdf->setCellPaddings(0, 2, 2, 2);
                $pdf->setFontStyle(null, 'B', 11);
                $pdf->Cell($maxCellWidth, 0, $day->getWeekdayName().' '.$this->asString($day->getDay()), false, 1, 'L');
                /** @var ExportCalendarToListDayHourItem $hour */
                foreach ($day->getHours() as $hour) {
                    if ($hour->getMaxStudentRows() > 0) {
                        $pdf->setCellPaddings(1, 1, 1, 1);
                        $pdf->setFontStyle(null, 'B', 8);
                        $pdf->SetFillColor(224, 235, 255);
                        // group row
                        $pdf->Cell(self::FIRST_CELL_WIDTH, 0, 'Group', true, 0, 'L', true);
                        $eventsAmount = count($hour->getEvents());
                        /** @var Event $event */
                        foreach ($hour->getEvents() as $event) {
                            $pdf->Cell(self::CELL_WIDTH, 0, $event->getGroup()->getCode(), true, 0, 'L', true);
                        }
                        if ($eventsAmount < 5) {
                            $this->drawEmptyCells($pdf, 5 - $eventsAmount, true);
                        } else {
                            // TODO break new table row
                        }
                        // room row
                        $pdf->Cell(self::FIRST_CELL_WIDTH, 0, 'Room', true, 0, 'L', true);
                        $eventsAmount = count($hour->getEvents());
                        /** @var Event $event */
                        foreach ($hour->getEvents() as $event) {
                            $pdf->Cell(self::CELL_WIDTH, 0, $event->getClassroomString(), true, 0, 'L', true);
                        }
                        if ($eventsAmount < 5) {
                            $this->drawEmptyCells($pdf, 5 - $eventsAmount, true);
                        } else {
                            // TODO break new table row
                        }
                        // teacher row
                        $pdf->Cell(self::FIRST_CELL_WIDTH, 0, 'Teacher', true, 0, 'L', true);
                        $eventsAmount = count($hour->getEvents());
                        /** @var Event $event */
                        foreach ($hour->getEvents() as $event) {
                            $pdf->Cell(self::CELL_WIDTH, 0, $event->getTeacher()->getName(), true, 0, 'L', true);
                        }
                        if ($eventsAmount < 5) {
                            $this->drawEmptyCells($pdf, 5 - $eventsAmount, true);
                        } else {
                            // TODO break new table row
                        }
                        // book row
                        $pdf->Cell(self::FIRST_CELL_WIDTH, 0, 'Book', true, 0, 'L', true);
                        $eventsAmount = count($hour->getEvents());
                        /** @var Event $event */
                        foreach ($hour->getEvents() as $event) {
                            $pdf->Cell(self::CELL_WIDTH, 0, $event->getGroup()->getBook(), true, 0, 'L', true);
                        }
                        if ($eventsAmount < 5) {
                            $this->drawEmptyCells($pdf, 5 - $eventsAmount, true);
                        } else {
                            // TODO break new table row
                        }
                        $pdf->SetFillColor(255, 255, 255);
                        // students row
                        $maxStudentRows = $hour->getMaxStudentRows();
                        if ($maxStudentRows > 0) {
                            for ($studentIteratorIndex = 0; $studentIteratorIndex < $maxStudentRows; ++$studentIteratorIndex) {
                                $pdf->setFontStyle(null, 'B', 8);
                                $pdf->Cell(self::FIRST_CELL_WIDTH, 0, (0 === $studentIteratorIndex ? $hour->getRangeName() : ''), true, 0, 'L', true);
                                $pdf->setFontStyle(null, '', 8);
                                $eventsAmount = count($hour->getEvents());
                                /** @var Event $event */
                                foreach ($hour->getEvents() as $event) {
                                    $studentName = '';
                                    if ($studentIteratorIndex < count($event->getStudents())) {
                                        $studentName = $event->getStudents()[$studentIteratorIndex]->getFullname();
                                    }
                                    $pdf->Cell(self::CELL_WIDTH, 0, $studentName, true, 0, 'L', true);
                                }
                                if ($eventsAmount < 5) {
                                    $this->drawEmptyCells($pdf, 5 - $eventsAmount, true);
                                } else {
                                    // TODO break new table row
                                }
                            }
                        }
                    }
                }
                if ($iteratorDayIndex !== $daysAmount) {
                    $this->buildNewPage($pdf, $leftMargin, $rightMargin);
                }
            }
        }

        return $pdf;
    }

    private function asString(DateTimeInterface $date): string
    {
        return $date->format('d/m/Y');
    }

    private function drawEmptyCells(TCPDF $pdf, int $columns, bool $fill): void
    {
        for ($index = 0; $index < $columns; ++$index) {
            $pdf->Cell(self::CELL_WIDTH, 0, '', true, ($index === $columns - 1 ? 1 : 0), 'L', $fill);
        }
    }

    private function buildNewPage(TCPDF $pdf, $leftMargin, $rightMargin): void
    {
        // Add start page
        $pdf->AddPage(PDF_PAGE_ORIENTATION, PDF_PAGE_FORMAT, true, true);
        $pdf->SetXY($leftMargin, $rightMargin);
    }
}
