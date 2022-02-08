<?php

namespace App\Service;

use App\Enum\ReceiptYearMonthEnum;
use App\Repository\InvoiceRepository;
use App\Repository\ReceiptRepository;
use App\Repository\SpendingRepository;
use DateInterval;
use DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartsFactoryService
{
    private const RED = 'rgb(255, 99, 132)';
    private const GREEN = 'rgb(75, 192, 192)';
    private const BLUE = 'rgb(54, 162, 235)';

    private TranslatorInterface $ts;
    private ReceiptRepository $rr;
    private InvoiceRepository $ir;
    private SpendingRepository $sr;
    private ChartBuilderInterface $cb;

    public function __construct(TranslatorInterface $ts, ReceiptRepository $rr, InvoiceRepository $ir, SpendingRepository $sr, ChartBuilderInterface $cb)
    {
        $this->ts = $ts;
        $this->rr = $rr;
        $this->ir = $ir;
        $this->sr = $sr;
        $this->cb = $cb;
    }

    public function buildLastYearResultsChart(): Chart
    {
        $labels = [];
        $sales = [];
        $expenses = [];
        $results = [];
        $date = new DateTime();
        $date->sub(new DateInterval('P24M'));
        $interval = new DateInterval('P1M');
        for ($i = 0; $i <= 24; ++$i) {
            $sale = $this->rr->getMonthlyIncomingsAmountForDate($date);
            $sale += $this->ir->getMonthlyIncomingsAmountForDate($date);
            $expense = $this->sr->getMonthlyExpensesAmountForDate($date);
            $result = $sale - $expense;
            $labels[] = ReceiptYearMonthEnum::getShortTranslatedMonthEnumArray()[(int) $date->format('n')].'. '.$date->format('Y');
            $sales[] = round($sale);
            $expenses[] = round($expense);
            $results[] = round($result);
            $date->add($interval);
        }
        $chart = $this->cb->createChart(Chart::TYPE_LINE);
        $chart
            ->setData([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.sales'),
                        'data' => $sales,
                        'borderColor' => self::GREEN,
                        'backgroundColor' => self::GREEN,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.expenses'),
                        'data' => $expenses,
                        'borderColor' => self::RED,
                        'backgroundColor' => self::RED,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.results'),
                        'data' => $results,
                        'borderColor' => self::BLUE,
                        'backgroundColor' => self::BLUE,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                ],
            ])
            ->setOptions([
                'aspectRatio' => 4,
                'scales' => [
                    'yAxes' => [
                        'ticks' => [
                            'display' => true,
                        ],
                    ],
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ])
        ;

        return $chart;
    }
}
