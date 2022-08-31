<?php

namespace App\Pdf;

use App\Entity\Student;
use App\Service\SmartAssetsHelperService;
use DateTimeImmutable;
use IntlDateFormatter;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TCPDF;

class StudentImageRightsBuilderPdf
{
    private TCPDFController $tcpdf;
    private SmartAssetsHelperService $sahs;
    private TranslatorInterface $ts;
    private ParameterBagInterface $pb;
    private string $pwt;

    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, TranslatorInterface $ts, ParameterBagInterface $pb)
    {
        $this->tcpdf = $tcpdf;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->pb = $pb;
        $this->pwt = $pb->get('project_web_title');
    }

    public function build(Student $student): TCPDF
    {
        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs, $this->pb);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.imagerigths.pdf_metadata_title'));
        $pdf->SetSubject($this->ts->trans('backend.admin.imagerigths.pdf_metadata_subject'));
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
        $pdf->setFontStyle(null, '', 11);
        // Description
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.description1', ['%main_brand_title%' => $this->pwt]), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.description2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Contact name
        $contactName = $student->getName().' '.$student->getSurname();
        $contactDni = $student->getDni();
        if ($student->getParent()) {
            $contactName = $student->getParent()->getName().' '.$student->getParent()->getSurname();
            $contactDni = $student->getParent()->getDni();
        }
        $pdf->Write(0, $this->ts->trans(($student->getParent() ? 'backend.admin.imagerigths.contact_name_with_parent' : 'backend.admin.imagerigths.contact_name_without_parent'), ['%name%' => $contactName]), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.contact_dni', ['%contact_dni%' => $contactDni]), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.imagerigths.autortization1', ['%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld()]), 0, 'L', false, 1);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->SetX(55);
        $pdf->Rect(51, $pdf->GetY() + 1, 3, 3);
        $pdf->MultiCell(125, 0, $this->ts->trans('backend.admin.imagerigths.autortization2', ['%student_name%' => $student->getName(), '%years_old%' => $student->getYearsOld()]), 0, 'L', false, 1);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        // Registration date
        $today = new DateTimeImmutable();
        $df = new IntlDateFormatter('ca_ES', IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
        $df->setPattern('MMMM');
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.registration_date', ['%day%' => $today->format('j'), '%month%' => $df->format($today), '%year%' => $today->format('Y')]), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->setFontStyle(null, 'B', 11);
        $pdf->Write(0, $this->ts->trans('backend.admin.imagerigths.sign'), '', false, 'L', true);

        return $pdf;
    }
}
