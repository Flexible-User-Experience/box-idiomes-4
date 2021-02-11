<?php

namespace App\Service;

use App\Enum\ReceiptYearMonthEnum;
use App\Repository\InvoiceRepository;
use App\Repository\ReceiptRepository;
use App\Repository\SpendingRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\NonUniqueResultException;
use SaadTazi\GChartBundle\DataTable\DataCell;
use SaadTazi\GChartBundle\DataTable\DataColumn;
use SaadTazi\GChartBundle\DataTable\DataRow;
use SaadTazi\GChartBundle\DataTable\DataTable;
use SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChartsFactoryService
{
    private TranslatorInterface $ts;
    private ReceiptRepository $rr;
    private InvoiceRepository $ir;
    private SpendingRepository $sr;

    public function __construct(TranslatorInterface $ts, ReceiptRepository $rr, InvoiceRepository $ir, SpendingRepository $sr)
    {
        $this->ts = $ts;
        $this->rr = $rr;
        $this->ir = $ir;
        $this->sr = $sr;
    }

    /**
     * @throws InvalidColumnTypeException
     * @throws NonUniqueResultException
     */
    public function buildLastYearResultsChart(): DataTable
    {
        $dt = new DataTable();
        $dt->addColumnObject(new DataColumn('id1', 'title', 'string'));
        $dt->addColumnObject(new DataColumn('id2', $this->ts->trans('backend.admin.block.charts.sales', [], 'messages'), 'number'));
        $dt->addColumnObject(new DataColumn('id3', $this->ts->trans('backend.admin.block.charts.expenses', [], 'messages'), 'number'));
        $dt->addColumnObject(new DataColumn('id4', $this->ts->trans('backend.admin.block.charts.results', [], 'messages'), 'number'));

        $date = new DateTime();
        $date->sub(new DateInterval('P12M'));
        $interval = new DateInterval('P1M');
        for ($i = 0; $i <= 12; ++$i) {
            $sales = $this->rr->getMonthlyIncomingsAmountForDate($date);
            $sales += $this->ir->getMonthlyIncomingsAmountForDate($date);
            $expenses = $this->sr->getMonthlyExpensesAmountForDate($date);
            $results = $sales - $expenses;
            $dt->addRowObject($this->buildResultsCellsRow($date, $sales, $expenses, $results));
            $date->add($interval);
        }

        return $dt;
    }

    private function buildResultsCellsRow(DateTimeInterface $key, $sales, $expenses, $results): DataRow
    {
        return new DataRow([
                new DataCell(ReceiptYearMonthEnum::getShortTranslatedMonthEnumArray()[(int) $key->format('n')].'. '.$key->format('y')),
                new DataCell($sales, number_format($sales, 0, ',', '.').'€'),
                new DataCell($expenses, number_format($expenses, 0, ',', '.').'€'),
                new DataCell($results, number_format($results, 0, ',', '.').'€'),
            ]
        );
    }
}
