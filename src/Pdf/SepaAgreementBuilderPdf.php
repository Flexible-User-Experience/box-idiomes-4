<?php

namespace App\Pdf;

use App\Entity\Student;
use App\Service\SmartAssetsHelperService;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TCPDF;

class SepaAgreementBuilderPdf
{
    private TCPDFController $tcpdf;
    private SmartAssetsHelperService $sahs;
    private TranslatorInterface $ts;
    private string $pwt;
    private string $bn;

    public function __construct(TCPDFController $tcpdf, SmartAssetsHelperService $sahs, TranslatorInterface $ts, ParameterBagInterface $pb)
    {
        $this->tcpdf = $tcpdf;
        $this->sahs = $sahs;
        $this->ts = $ts;
        $this->pwt = $pb->get('project_web_title');
        $this->bn = $pb->get('boss_name');
    }

    public function build(Student $student): TCPDF
    {
        /** @var BaseTcpdf $pdf */
        $pdf = $this->tcpdf->create($this->sahs);

        $maxCellWidth = BaseTcpdf::PDF_WIDTH - BaseTcpdf::PDF_MARGIN_LEFT - BaseTcpdf::PDF_MARGIN_RIGHT;

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pwt);
        $pdf->SetTitle($this->ts->trans('backend.admin.sepa_agreement'));
        $pdf->SetSubject($this->ts->trans('backend.admin.sepa_agreement'));
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
        // description
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.first_text'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text1'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text2'), '', false, 'L', true);
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_SMALL);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.text3'), '', false, 'L', true);
        // table
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->setCellPaddings(2, 1, 0, 0);
        $pdf->setCellMargins(1, 0, 1, 0);
        // contact
        $subject = $student;
        if ($student->getParent()) {
            $subject = $student->getParent();
        }
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_name').'</strong><br>'.$subject->getName().' '.$subject->getSurname(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_dni').'</strong><br>'.$subject->getDni(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.parent_address').'</strong><br>'.$subject->getAddress(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.contact_phone').'</strong><br>'.$subject->getPhone(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell($maxCellWidth, 12.5, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.bank_name').'</strong><br>'.$subject->getBank()->getName(), 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        // iban
        $pdf->MultiCell($maxCellWidth, 7, '<strong>'.$this->ts->trans('backend.admin.sepaagreement.bank_account').'</strong>', 1, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(1, 0, 0, 0);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN1part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN2part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN3part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN4part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN5part(), 1, 'C', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->MultiCell(25, 7, $subject->getBank()->getBAN6part(), 1, 'C', false, 1, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellMargins(1, 0, 1, 0);
        $pdf->setCellPaddings(0, 0, 0, 0);
        // description legal
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->Write(0, $this->ts->trans('backend.admin.sepaagreement.end_text'), '', false, 'L', true);
        // signs
        $pdf->Ln(BaseTcpdf::MARGIN_VERTICAL_BIG);
        $pdf->MultiCell($maxCellWidth / 2, 7, '<strong>'.$this->bn.'</strong>', 0, 'L', false, 0, '', '', true, 0, true, true, 0, 'T', false);
        $pdf->setCellPaddings(0, 0, 2, 0);
        $pdf->MultiCell($maxCellWidth / 2, 7, '<strong>'.$this->ts->trans('backend.admin.imagerigths.sign').'</strong>', 0, 'R', false, 1, '', '', true, 0, true, true, 0, 'T', false);

        return $pdf;
    }
}
