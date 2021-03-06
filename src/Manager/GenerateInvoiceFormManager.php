<?php

namespace App\Manager;

use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Student;
use App\Enum\InvoiceYearMonthEnum;
use App\Form\Model\GenerateInvoiceItemModel;
use App\Form\Model\GenerateInvoiceModel;
use App\Repository\EventRepository;
use App\Repository\InvoiceRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class GenerateInvoiceFormManager.
 *
 * @category Manager
 */
class GenerateInvoiceFormManager extends AbstractGenerateReceiptInvoiceFormManager
{
    /**
     * @var InvoiceRepository
     */
    private $ir;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceFormManager constructor.
     */
    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManager $em, TranslatorInterface $ts, StudentRepository $sr, EventRepository $er, InvoiceRepository $ir)
    {
        parent::__construct($logger, $kernel, $em, $ts, $sr, $er);
        $this->ir = $ir;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return GenerateInvoiceModel
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function buildFullModelForm($year, $month)
    {
        $generateInvoice = new GenerateInvoiceModel();
        $generateInvoice
            ->setYear($year)
            ->setMonth($month)
        ;
        $studentsInGroupLessons = $this->sr->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInGroupLessons as $student) {
            /** @var Invoice $previousInvoice */
            $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentYearAndMonthOrNull($student, $year, $month);
            if (!is_null($previousInvoice)) {
                // old
                if (count($previousInvoice->getLines()) > 0) {
                    /** @var InvoiceLine $previousItem */
                    $previousItem = $previousInvoice->getLines()[0];
                    $generateInvoiceItem = new GenerateInvoiceItemModel();
                    $generateInvoiceItem
                        ->setStudentId($student->getId())
                        ->setStudentName($student->getFullCanonicalName())
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                        ->setIsPrivateLessonType(false)
                    ;
                    $generateInvoice->addItem($generateInvoiceItem);
                }
            } else {
                // new
                $generateInvoiceItem = new GenerateInvoiceItemModel();
                $generateInvoiceItem
                    ->setStudentId($student->getId())
                    ->setStudentName($student->getFullCanonicalName())
                    ->setUnits(1)
                    ->setUnitPrice($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscount())
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                    ->setIsPrivateLessonType(false)
                ;
                $generateInvoice->addItem($generateInvoiceItem);
            }
        }

        return $generateInvoice;
    }

    /**
     * @param array $requestArray
     *
     * @return GenerateInvoiceModel
     */
    public function transformRequestArrayToModel($requestArray)
    {
        $generateInvoice = new GenerateInvoiceModel();
        if (array_key_exists('year', $requestArray)) {
            $generateInvoice->setYear(intval($requestArray['year']));
        }
        if (array_key_exists('month', $requestArray)) {
            $generateInvoice->setMonth(intval($requestArray['month']));
        }
        if (array_key_exists('items', $requestArray)) {
            $items = $requestArray['items'];
            /** @var array $item */
            foreach ($items as $item) {
                if (array_key_exists('units', $item) && array_key_exists('unitPrice', $item) && array_key_exists('discount', $item) && array_key_exists('studentId', $item)) {
                    $studentId = intval($item['studentId']);
                    /** @var Student $student */
                    $student = $this->sr->find($studentId);
                    if ($student) {
                        $generateInvoiceItem = new GenerateInvoiceItemModel();
                        $generateInvoiceItem
                            ->setStudentId($student->getId())
                            ->setStudentName($student->getFullCanonicalName())
                            ->setUnits($this->parseStringToFloat($item['units']))
                            ->setUnitPrice($this->parseStringToFloat($item['unitPrice']))
                            ->setDiscount($this->parseStringToFloat($item['discount']))
                            ->setIsReadyToGenerate(array_key_exists('isReadyToGenerate', $item))
                            ->setIsPreviouslyGenerated(array_key_exists('isPreviouslyGenerated', $item))
                        ;
                        $generateInvoice->addItem($generateInvoiceItem);
                    }
                }
            }
        }

        return $generateInvoice;
    }

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistFullModelForm(GenerateInvoiceModel $generateInvoiceModel)
    {
        $recordsParsed = 0;
        /** @var GenerateInvoiceItemModel $generateInvoiceItemModel */
        foreach ($generateInvoiceModel->getItems() as $generateInvoiceItemModel) {
            if ($generateInvoiceItemModel->isReadyToGenerate()) {
                ++$recordsParsed;
                /** @var Student|null $student */
                $student = $this->sr->find($generateInvoiceItemModel->getStudentId());
                if ($generateInvoiceItemModel->isPreviouslyGenerated()) {
                    /** @var Invoice $previousInvoice */
                    $previousInvoice = $this->ir->findOnePreviousInvoiceByStudentIdYearAndMonthOrNull($generateInvoiceItemModel->getStudentId(), $generateInvoiceModel->getYear(), $generateInvoiceModel->getMonth());
                    if (1 === count($previousInvoice->getLines())) {
                        /** @var InvoiceLine $invoiceLine */
                        $invoiceLine = $previousInvoice->getLines()[0];
                        $invoiceLine
                            ->setStudent($student)
                            ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', ['%month%' => InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$generateInvoiceModel->getMonth()], '%year%' => $generateInvoiceModel->getYear()], 'messages'))
                            ->setUnits($generateInvoiceItemModel->getUnits())
                            ->setPriceUnit($generateInvoiceItemModel->getUnitPrice())
                            ->setDiscount($generateInvoiceItemModel->getDiscount())
                            ->setTotal($generateInvoiceItemModel->getUnits() * $generateInvoiceItemModel->getUnitPrice() - $generateInvoiceItemModel->getDiscount())
                        ;
                        $previousInvoice
                            ->setTaxPercentage(0)
                            ->setBaseAmount($invoiceLine->getTotal())
                            ->setIrpfPercentage($previousInvoice->calculateIrpfPercentatge())
                            ->setTotalAmount($invoiceLine->getTotal() - $previousInvoice->getIrpfPercentage())
                        ;
                        $this->em->flush();
                    }
                } else {
                    // create new invoice
                    $invoiceLine = new InvoiceLine();
                    $invoiceLine
                        ->setStudent($student)
                        ->setDescription($this->ts->trans('backend.admin.invoiceLine.generator.line', ['%month%' => InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$generateInvoiceModel->getMonth()], '%year%' => $generateInvoiceModel->getYear()], 'messages'))
                        ->setUnits($generateInvoiceItemModel->getUnits())
                        ->setPriceUnit($generateInvoiceItemModel->getUnitPrice())
                        ->setDiscount($generateInvoiceItemModel->getDiscount())
                        ->setTotal($generateInvoiceItemModel->getUnits() * $generateInvoiceItemModel->getUnitPrice() - $generateInvoiceItemModel->getDiscount())
                    ;
                    $invoice = new Invoice();
                    $invoice
                        ->setStudent($student)
                        ->setPerson($student->getParent() ? $student->getParent() : null)
                        ->setDate(new \DateTime())
                        ->setIsPayed(false)
                        ->setIsSepaXmlGenerated(false)
                        ->setIsSended(false)
                        ->setYear($generateInvoiceModel->getYear())
                        ->setMonth($generateInvoiceModel->getMonth())
                        ->addLine($invoiceLine)
                        ->setIrpfPercentage($invoice->calculateIrpfPercentatge())
                        ->setTaxPercentage(0)
                        ->setTotalAmount($invoiceLine->getTotal() - $invoice->getIrpfPercentage())
                    ;
                    $this->em->persist($invoice);
                }
            }
        }
        $this->em->flush();

        return $recordsParsed;
    }
}
