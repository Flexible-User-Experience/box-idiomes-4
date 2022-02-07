<?php

namespace App\Controller\Admin;

use App\Manager\GenerateReceiptFormManager;
use App\Manager\ReceiptManager;
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
    protected ReceiptManager $rm;
    protected ReceiptBuilderPdf $rbp;
    protected ReceiptReminderBuilderPdf $rrbp;
    protected XmlSepaBuilderService $xsbs;
    protected NotificationService $ns;
    protected TranslatorInterface $ts;

    public function __construct(ManagerRegistry $mr, GenerateReceiptFormManager $grfm, ReceiptManager $rm, ReceiptBuilderPdf $rbp, ReceiptReminderBuilderPdf $rrbp, XmlSepaBuilderService $xsbs, NotificationService $ns, TranslatorInterface $ts)
    {
        $this->mr = $mr;
        $this->grfm = $grfm;
        $this->rm = $rm;
        $this->rbp = $rbp;
        $this->rrbp = $rrbp;
        $this->xsbs = $xsbs;
        $this->ns = $ns;
        $this->ts = $ts;
    }
}
