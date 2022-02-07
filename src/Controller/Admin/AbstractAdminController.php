<?php

namespace App\Controller\Admin;

use App\Manager\EventManager;
use App\Manager\GenerateInvoiceFormManager;
use App\Manager\GenerateReceiptFormManager;
use App\Manager\ReceiptManager;
use App\Pdf\InvoiceBuilderPdf;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
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
    protected ReceiptBuilderPdf $rbp;
    protected InvoiceBuilderPdf $ibp;
    protected ReceiptReminderBuilderPdf $rrbp;
    protected XmlSepaBuilderService $xsbs;
    protected NotificationService $ns;
    protected TranslatorInterface $ts;

    public function __construct(ManagerRegistry $mr, GenerateReceiptFormManager $grfm, GenerateInvoiceFormManager $gifm, EventManager $em, ReceiptManager $rm, ReceiptBuilderPdf $rbp, InvoiceBuilderPdf $ibp, ReceiptReminderBuilderPdf $rrbp, XmlSepaBuilderService $xsbs, NotificationService $ns, TranslatorInterface $ts)
    {
        $this->mr = $mr;
        $this->grfm = $grfm;
        $this->gifm = $gifm;
        $this->em = $em;
        $this->rm = $rm;
        $this->rbp = $rbp;
        $this->ibp = $ibp;
        $this->rrbp = $rrbp;
        $this->xsbs = $xsbs;
        $this->ns = $ns;
        $this->ts = $ts;
    }
}
