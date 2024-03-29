<?php

namespace App\Manager;

use App\Entity\Receipt;
use App\Entity\ReceiptLine;
use App\Entity\Student;
use App\Entity\TrainingCenter;
use App\Enum\ReceiptYearMonthEnum;
use App\Form\Model\GenerateReceiptItemModel;
use App\Form\Model\GenerateReceiptModel;
use App\Message\NewReceiptCreatedEmailMessage;
use App\Repository\EventRepository;
use App\Repository\ReceiptRepository;
use App\Repository\StudentRepository;
use App\Repository\TrainingCenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GenerateReceiptFormManager extends AbstractGenerateReceiptInvoiceFormManager
{
    private ReceiptRepository $rr;
    private EventManager $eem;
    private ParameterBagInterface $parameterBag;
    private MessageBusInterface $bus;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManagerInterface $em, TranslatorInterface $ts, TrainingCenterRepository $tcr, StudentRepository $sr, EventRepository $er, ReceiptRepository $rr, EventManager $eem, ParameterBagInterface $parameterBag, MessageBusInterface $bus)
    {
        parent::__construct($logger, $kernel, $em, $ts, $tcr, $sr, $er);
        $this->rr = $rr;
        $this->eem = $eem;
        $this->parameterBag = $parameterBag;
        $this->bus = $bus;
    }

    public function buildFullModelForm(GenerateReceiptModel $generateReceipt): GenerateReceiptModel
    {
        // group lessons
        $studentsInGroupLessons = $this->sr->getGroupLessonStudentsInEventsForReceiptModelWithValidTariff($generateReceipt);
        /** @var Student $student */
        foreach ($studentsInGroupLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNull($student, $generateReceipt->getYear(), $generateReceipt->getMonth());
            if (!is_null($previousReceipt)) {
                // old
                if (count($previousReceipt->getLines()) > 0) {
                    /** @var ReceiptLine $previousItem */
                    $previousItem = $previousReceipt->getLines()[0];
                    $generateReceiptItem = new GenerateReceiptItemModel();
                    $generateReceiptItem
                        ->setTrainingCenterId($generateReceipt->getTrainingCenter()->getId())
                        ->setStudentId($student->getId())
                        ->setStudentName($student->getFullCanonicalName())
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                        ->setIsPrivateLessonType(false)
                    ;
                    $generateReceipt->addItem($generateReceiptItem);
                }
            } else {
                // new
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
                    ->setTrainingCenterId($generateReceipt->getTrainingCenter()->getId())
                    ->setStudentId($student->getId())
                    ->setStudentName($student->getFullCanonicalName())
                    ->setUnits(1)
                    ->setUnitPrice($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscountWithExtraSonDiscount($this->parameterBag->get('project_discount_extra_son')))
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                    ->setIsPrivateLessonType(false)
                ;
                if ($generateReceiptItem->getUnitPrice() > 0 && $generateReceiptItem->getUnitPrice() > $generateReceiptItem->getDiscount()) {
                    $generateReceipt->addItem($generateReceiptItem);
                }
            }
        }
        // private lessons (in previous month period)
        $oldYear = $generateReceipt->getYear();
        $oldMonth = $generateReceipt->getMonth();
        $year = $oldYear;
        $month = $oldMonth;
        --$month;
        if (0 === $month) {
            $month = 12;
            --$year;
        }
        $generateReceipt
            ->setYear($year)
            ->setMonth($month)
        ;
        $studentsInPrivateLessons = $this->sr->getPrivateLessonStudentsInEventsForReceiptModelSortedBySurnameWithValidTariff($generateReceipt);
        /** @var Student $student */
        foreach ($studentsInPrivateLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNull($student, $oldYear, $oldMonth);
            if (!is_null($previousReceipt)) {
                // old
                if (count($previousReceipt->getLines()) > 0) {
                    /** @var ReceiptLine $previousItem */
                    $previousItem = $previousReceipt->getLines()[0];
                    $generateReceiptItem = new GenerateReceiptItemModel();
                    $generateReceiptItem
                        ->setTrainingCenterId($generateReceipt->getTrainingCenter()->getId())
                        ->setStudentId($student->getId())
                        ->setStudentName($student->getFullCanonicalName())
                        ->setUnits($previousItem->getUnits())
                        ->setUnitPrice($previousItem->getPriceUnit())
                        ->setDiscount($previousItem->getDiscount())
                        ->setIsReadyToGenerate(false)
                        ->setIsPreviouslyGenerated(true)
                        ->setIsPrivateLessonType(true)
                    ;
                    $generateReceipt->addItem($generateReceiptItem);
                }
            } else {
                // new
                $privateLessons = $this->er->getPrivateLessonsByStudentYearAndMonth($student, $generateReceipt->getYear(), $generateReceipt->getMonth());
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
                    ->setTrainingCenterId($generateReceipt->getTrainingCenter()->getId())
                    ->setStudentId($student->getId())
                    ->setStudentName($student->getFullCanonicalName())
                    ->setUnits((float) count($privateLessons))
                    ->setUnitPrice($this->eem->getCurrentPrivateLessonsTariffForEvents($privateLessons)->getPrice())
                    ->setDiscount(0)
                    ->setIsReadyToGenerate(true)
                    ->setIsPreviouslyGenerated(false)
                    ->setIsPrivateLessonType(true)
                ;
                if ($generateReceiptItem->getUnits() > 0 && $generateReceiptItem->getUnitPrice() > 0) {
                    $generateReceipt->addItem($generateReceiptItem);
                }
            }
        }
        $generateReceipt
            ->setYear($oldYear)
            ->setMonth($oldMonth)
        ;

        return $generateReceipt;
    }

    public function transformRequestArrayToModel(array $requestArray): GenerateReceiptModel
    {
        $generateReceipt = new GenerateReceiptModel();
        if (array_key_exists('year', $requestArray)) {
            $generateReceipt->setYear((int) $requestArray['year']);
        }
        if (array_key_exists('month', $requestArray)) {
            $generateReceipt->setMonth((int) $requestArray['month']);
        }
        if (array_key_exists('trainingCenter', $requestArray)) {
            $trainingCenterId = (int) $requestArray['trainingCenter'];
            /** @var TrainingCenter $trainingCenter */
            $trainingCenter = $this->tcr->find($trainingCenterId);
            $generateReceipt->setTrainingCenter($trainingCenter);
        }
        if (array_key_exists('items', $requestArray)) {
            $items = $requestArray['items'];
            /** @var array $item */
            foreach ($items as $item) {
                if (array_key_exists('units', $item) && array_key_exists('unitPrice', $item) && array_key_exists('discount', $item) && array_key_exists('studentId', $item)) {
                    $studentId = (int) $item['studentId'];
                    /** @var Student $student */
                    $student = $this->sr->find($studentId);
                    if ($student) {
                        $generateReceiptItem = new GenerateReceiptItemModel();
                        $generateReceiptItem
                            ->setTrainingCenterId($trainingCenterId)
                            ->setStudentId($student->getId())
                            ->setStudentName($student->getFullCanonicalName())
                            ->setUnits($this->parseStringToFloat($item['units']))
                            ->setUnitPrice($this->parseStringToFloat($item['unitPrice']))
                            ->setDiscount($this->parseStringToFloat($item['discount']))
                            ->setIsReadyToGenerate(array_key_exists('isReadyToGenerate', $item))
                            ->setIsPreviouslyGenerated(array_key_exists('isPreviouslyGenerated', $item))
                            ->setIsPrivateLessonType(array_key_exists('isPrivateLessonType', $item))
                        ;
                        $generateReceipt->addItem($generateReceiptItem);
                    }
                }
            }
        }

        return $generateReceipt;
    }

    public function persistFullModelForm(GenerateReceiptModel $generateReceiptModel, $markReceiptAsSended = false): int
    {
        $recordsParsed = 0;
        /** @var GenerateReceiptItemModel $generateReceiptItemModel */
        foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
            if ($generateReceiptItemModel->isReadyToGenerate()) {
                if (!$generateReceiptItemModel->isPrivateLessonType()) {
                    // group lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                    $description = $this->ts->trans('backend.admin.invoiceLine.generator.group_lessons_line', ['%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$generateReceiptModel->getMonth()], '%year%' => $generateReceiptModel->getYear()], 'messages');
                    $isForPrivateLessons = false;
                } else {
                    // private lessons
                    $month = $generateReceiptModel->getMonth() - 1;
                    $year = $generateReceiptModel->getYear();
                    if (0 === $month) {
                        $month = 12;
                        --$year;
                    }
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                    $description = $this->ts->trans('backend.admin.invoiceLine.generator.private_lessons_line', ['%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year], 'messages');
                    $isForPrivateLessons = true;
                }
                ++$recordsParsed;
                /** @var Student $student */
                $student = $this->sr->find($generateReceiptItemModel->getStudentId());
                /** @var TrainingCenter $trainingCenter */
                $trainingCenter = $this->tcr->find($generateReceiptItemModel->getTrainingCenterId());
                if (!is_null($previousReceipt)) {
                    // update existing receipt
                    if (1 === count($previousReceipt->getLines())) {
                        $previousReceipt->setDate(new \DateTimeImmutable());
                        /** @var ReceiptLine $receiptLine */
                        $receiptLine = $previousReceipt->getLines()[0];
                        $receiptLine
                            ->setStudent($student)
                            ->setDescription($description)
                            ->setUnits($generateReceiptItemModel->getUnits())
                            ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                            ->setDiscount($generateReceiptItemModel->getDiscount())
                            ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                        ;
                        $previousReceipt
                            ->setTrainingCenter($trainingCenter)
                            ->setBaseAmount($receiptLine->getTotal())
                            ->setIsForPrivateLessons($isForPrivateLessons)
                        ;
                        if ($markReceiptAsSended) {
                            $previousReceipt
                                ->setIsSended(true)
                                ->setSendDate(new \DateTimeImmutable())
                            ;
                        }
                        $this->em->flush();
                    }
                } else {
                    // create new receipt
                    $receiptLine = new ReceiptLine();
                    $receiptLine
                        ->setStudent($student)
                        ->setDescription($description)
                        ->setUnits($generateReceiptItemModel->getUnits())
                        ->setPriceUnit($generateReceiptItemModel->getUnitPrice())
                        ->setDiscount($generateReceiptItemModel->getDiscount())
                        ->setTotal($generateReceiptItemModel->getUnits() * $generateReceiptItemModel->getUnitPrice() - $generateReceiptItemModel->getDiscount())
                    ;
                    $receipt = new Receipt();
                    $receipt
                        ->setDate(new \DateTimeImmutable())
                        ->setTrainingCenter($trainingCenter)
                        ->setStudent($student)
                        ->setPerson($student->getParent() ?: null)
                        ->setIsPayed(false)
                        ->setIsSepaXmlGenerated(false)
                        ->setIsSended(false)
                        ->setYear($generateReceiptModel->getYear())
                        ->setMonth($generateReceiptModel->getMonth())
                        ->addLine($receiptLine)
                        ->setIsForPrivateLessons($isForPrivateLessons)
                    ;
                    if ($markReceiptAsSended) {
                        $receipt
                            ->setIsSended(true)
                            ->setSendDate(new \DateTimeImmutable())
                        ;
                    }
                    $this->em->persist($receipt);
                }
            }
        }
        $this->em->flush();

        return $recordsParsed;
    }

    public function persistAndDeliverFullModelForm(GenerateReceiptModel $generateReceiptModel): int
    {
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm call');
        $recordsParsed = $this->persistFullModelForm($generateReceiptModel, true);
        $this->logger->info('[GRFM] '.$recordsParsed.' records managed');
        if (0 < $recordsParsed) {
            $ids = [];
            /** @var GenerateReceiptItemModel $generateReceiptItemModel */
            foreach ($generateReceiptModel->getItems() as $generateReceiptItemModel) {
                if (!$generateReceiptItemModel->isPrivateLessonType()) {
                    // group lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                } else {
                    // private lessons
                    /** @var Receipt $previousReceipt */
                    $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentIdYearAndMonthOrNull($generateReceiptItemModel->getStudentId(), $generateReceiptModel->getYear(), $generateReceiptModel->getMonth());
                }
                if ($previousReceipt && $generateReceiptItemModel->isReadyToGenerate() && 1 === count($previousReceipt->getLines())) {
                    $ids[] = $previousReceipt->getId();
                }
            }
            if (count($ids) > 0) {
                foreach ($ids as $id) {
                    $this->bus->dispatch(new NewReceiptCreatedEmailMessage($id));
                }
            }
        }
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm EOF');

        return $recordsParsed;
    }
}
