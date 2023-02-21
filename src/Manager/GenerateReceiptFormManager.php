<?php

namespace App\Manager;

use App\Entity\Receipt;
use App\Entity\ReceiptLine;
use App\Entity\Student;
use App\Enum\ReceiptYearMonthEnum;
use App\Form\Model\GenerateReceiptItemModel;
use App\Form\Model\GenerateReceiptModel;
use App\Repository\EventRepository;
use App\Repository\ReceiptRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Contracts\Translation\TranslatorInterface;

class GenerateReceiptFormManager extends AbstractGenerateReceiptInvoiceFormManager
{
    private ReceiptRepository $rr;
    private EventManager $eem;
    private ParameterBagInterface $parameterBag;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManagerInterface $em, TranslatorInterface $ts, StudentRepository $sr, EventRepository $er, ReceiptRepository $rr, EventManager $eem, ParameterBagInterface $parameterBag)
    {
        parent::__construct($logger, $kernel, $em, $ts, $sr, $er);
        $this->rr = $rr;
        $this->eem = $eem;
        $this->parameterBag = $parameterBag;
    }

    private function commonFastGenerateReciptsForYearAndMonth(int $year, int $month, $enableEmailDelivery = false): int
    {
        $generatedReceiptsAmount = 0;

        // group lessons
        $studentsInGroupLessons = $this->sr->getGroupLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInGroupLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousGroupLessonsReceiptByStudentYearAndMonthOrNull($student, $year, $month);
            if (is_null($previousReceipt)) {
                // create and persist new receipt
                ++$generatedReceiptsAmount;
                $description = $this->ts->trans('backend.admin.invoiceLine.generator.group_lessons_line', ['%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year], 'messages');
                $receiptLine = new ReceiptLine();
                $receiptLine
                    ->setStudent($student)
                    ->setDescription($description)
                    ->setUnits(1)
                    ->setPriceUnit($student->getTariff()->getPrice())
                    ->setDiscount($student->calculateMonthlyDiscountWithExtraSonDiscount($this->parameterBag->get('project_discount_extra_son')))
                    ->setTotal($receiptLine->getPriceUnit() - $receiptLine->getDiscount())
                ;
                $receipt = new Receipt();
                $receipt
                    ->setDate(new \DateTimeImmutable())
                    ->setStudent($student)
                    ->setPerson($student->getParent() ?: null)
                    ->setIsPayed(false)
                    ->setIsSepaXmlGenerated(false)
                    ->setIsForPrivateLessons(false)
                    ->setIsSended(false)
                    ->setYear($year)
                    ->setMonth($month)
                    ->addLine($receiptLine)
                ;
                if ($enableEmailDelivery) {
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTimeImmutable())
                    ;
                }
                $this->em->persist($receipt);
            }
        }

        // private lessons (in previous month period)
        $oldYear = $year;
        $oldMonth = $month;
        --$month;
        if (0 === $month) {
            $month = 12;
            --$year;
        }
        $studentsInPrivateLessons = $this->sr->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
        /** @var Student $student */
        foreach ($studentsInPrivateLessons as $student) {
            /** @var Receipt $previousReceipt */
            $previousReceipt = $this->rr->findOnePreviousPrivateLessonsReceiptByStudentYearAndMonthOrNull($student, $oldYear, $oldMonth);
            if (is_null($previousReceipt)) {
                // create and persist new receipt
                ++$generatedReceiptsAmount;
                $description = $this->ts->trans('backend.admin.invoiceLine.generator.private_lessons_line', ['%month%' => ReceiptYearMonthEnum::getTranslatedMonthEnumArray()[$month], '%year%' => $year], 'messages');
                $receiptLine = new ReceiptLine();
                $receiptLine
                    ->setStudent($student)
                    ->setDescription($description)
                    ->setUnits($this->er->getPrivateLessonsAmountByStudentYearAndMonth($student, $year, $month))
                    ->setPriceUnit($this->eem->getCurrentPrivateLessonsTariffForEvents($studentsInPrivateLessons)->getPrice())
                    ->setDiscount(0)
                    ->setTotal($receiptLine->getPriceUnit() - $receiptLine->getDiscount())
                ;
                $receipt = new Receipt();
                $receipt
                    ->setDate(new \DateTimeImmutable())
                    ->setStudent($student)
                    ->setPerson($student->getParent() ?: null)
                    ->setIsPayed(false)
                    ->setIsSepaXmlGenerated(false)
                    ->setIsForPrivateLessons(true)
                    ->setIsSended(false)
                    ->setYear($oldYear)
                    ->setMonth($oldMonth)
                    ->addLine($receiptLine)
                ;
                if ($enableEmailDelivery) {
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTimeImmutable())
                    ;
                }
                $this->em->persist($receipt);
            }
        }
        $this->em->flush();

        if ($enableEmailDelivery) {
            $ids = [];
            $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth call');
            $this->logger->info('[GRFM] '.$generatedReceiptsAmount.' records managed');
            $receipts = $this->rr->findBy([
                'year' => $oldYear,
                'month' => $oldMonth,
                'isSended' => false,
            ]);
            if (count($receipts) > 0) {
                /** @var Receipt $receipt */
                foreach ($receipts as $receipt) {
                    $ids[] = $receipt->getId();
                    $receipt
                        ->setIsSended(true)
                        ->setSendDate(new \DateTimeImmutable())
                    ;
                }
                $this->em->flush();
                $phpBinaryFinder = new PhpExecutableFinder();
                $phpBinaryPath = $phpBinaryFinder->find();
                $command = 'nohup '.$phpBinaryPath.' '.$this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'console app:deliver:receipts:batch '.implode(' ', $ids).' --force --env='.$this->kernel->getEnvironment().' 2>&1 > /dev/null &';
                $this->logger->info('[GRFM] '.$command);
                $process = new Process([$command]);
                $process->setTimeout(null);
                $process->run();
            } else {
                $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth nothing send, all receipts are preivously sended.');
            }
        }
        $this->logger->info('[GRFM] commonFastGenerateReciptsForYearAndMonth EOF');

        return $generatedReceiptsAmount;
    }

    public function fastGenerateReciptsForYearAndMonth(int $year, int $month): int
    {
        return $this->commonFastGenerateReciptsForYearAndMonth($year, $month);
    }

    public function fastGenerateReciptsForYearAndMonthAndDeliverEmail(int $year, int $month): int
    {
        return $this->commonFastGenerateReciptsForYearAndMonth($year, $month, true);
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
        $studentsInPrivateLessons = $this->sr->getPrivateLessonStudentsInEventsForYearAndMonthSortedBySurnameWithValidTariff($year, $month);
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
                $privateLessons = $this->er->getPrivateLessonsByStudentYearAndMonth($student, $year, $month);
                $generateReceiptItem = new GenerateReceiptItemModel();
                $generateReceiptItem
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
            $phpBinaryFinder = new PhpExecutableFinder();
            $phpBinaryPath = $phpBinaryFinder->find();
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
                if ($previousReceipt && 1 === count($previousReceipt->getLines()) && $generateReceiptItemModel->isReadyToGenerate()) {
                    $ids[] = $previousReceipt->getId();
                }
            }
            if (count($ids) > 0) {
                $command = 'nohup '.$phpBinaryPath.' '.$this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'console app:deliver:receipts:batch '.implode(' ', $ids).' --force --env='.$this->kernel->getEnvironment().' 2>&1 > /dev/null &';
                $this->logger->info('[GRFM] '.$command);
                $process = new Process([$command]);
                $process->setTimeout(null);
                $process->run();
            }
        }
        $this->logger->info('[GRFM] persistAndDeliverFullModelForm EOF');

        return $recordsParsed;
    }
}
