<?php

namespace App\Controller\Admin;

use App\Manager\EventManager;
use App\Manager\GenerateInvoiceFormManager;
use App\Manager\GenerateReceiptFormManager;
use App\Manager\ReceiptManager;
use App\Pdf\InvoiceBuilderPdf;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
use App\Pdf\StudentEvaluationBuilderPdf;
use App\Repository\BankCreditorSepaRepository;
use App\Service\NotificationService;
use App\Service\XmlSepaBuilderService;
use Doctrine\Persistence\ManagerRegistry;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractAdminController extends CRUDController
{
    protected ManagerRegistry $mr;
    protected GenerateReceiptFormManager $grfm;
    protected GenerateInvoiceFormManager $gifm;
    protected EventManager $em;
    protected ReceiptManager $rm;
    protected BankCreditorSepaRepository $bcsr;
    protected ReceiptBuilderPdf $rbp;
    protected InvoiceBuilderPdf $ibp;
    protected ReceiptReminderBuilderPdf $rrbp;
    protected StudentEvaluationBuilderPdf $sebp;
    protected XmlSepaBuilderService $xsbs;
    protected NotificationService $ns;
    protected TranslatorInterface $ts;

    public function __construct(ManagerRegistry $mr, GenerateReceiptFormManager $grfm, GenerateInvoiceFormManager $gifm, EventManager $em, ReceiptManager $rm, BankCreditorSepaRepository $bcsr, ReceiptBuilderPdf $rbp, InvoiceBuilderPdf $ibp, ReceiptReminderBuilderPdf $rrbp, StudentEvaluationBuilderPdf $sebp, XmlSepaBuilderService $xsbs, NotificationService $ns, TranslatorInterface $ts)
    {
        $this->mr = $mr;
        $this->grfm = $grfm;
        $this->gifm = $gifm;
        $this->em = $em;
        $this->rm = $rm;
        $this->bcsr = $bcsr;
        $this->rbp = $rbp;
        $this->ibp = $ibp;
        $this->rrbp = $rrbp;
        $this->sebp = $sebp;
        $this->xsbs = $xsbs;
        $this->ns = $ns;
        $this->ts = $ts;
    }
}
