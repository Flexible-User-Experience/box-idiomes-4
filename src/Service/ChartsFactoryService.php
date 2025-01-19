<?php

namespace App\Service;

use App\Enum\ReceiptYearMonthEnum;
use App\Repository\InvoiceRepository;
use App\Repository\ReceiptRepository;
use App\Repository\SpendingRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final readonly class ChartsFactoryService
{
    private const string RED = 'rgb(255, 99, 132)';
    private const string GREEN = 'rgb(75, 192, 192)';
    private const string BLUE = 'rgb(54, 162, 235)';
    private const string BLACK = 'rgb(35, 35, 35)';

    public function __construct(
        private TranslatorInterface $ts,
        private ReceiptRepository $rr,
        private InvoiceRepository $ir,
        private SpendingRepository $sr,
        private ChartBuilderInterface $cb,
    ) {
    }

    /**
     * @throws \DateInvalidOperationException
     */
    public function buildLastYearResultsChart(): Chart
    {
        $labels = [];
        $sales = [];
        $expenses = [];
        $results = [];
        $zeros = [];
        $date = new \DateTime();
        $date->sub(new \DateInterval('P24M'));
        $interval = new \DateInterval('P1M');
        for ($i = 0; $i <= 24; ++$i) {
            $sale = $this->rr->getMonthlyIncomingsAmountForDate($date);
            $sale += $this->ir->getMonthlyIncomingsAmountForDate($date);
            $expense = $this->sr->getMonthlyExpensesAmountForDate($date);
            $result = $sale - $expense;
            $labels[] = ReceiptYearMonthEnum::getShortTranslatedMonthEnumArray()[(int) $date->format('n')].'. '.$date->format('Y');
            $sales[] = round($sale);
            $expenses[] = round($expense);
            $results[] = round($result);
            $zeros[] = 0.0;
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
                        'order' => 4,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.expenses'),
                        'data' => $expenses,
                        'borderColor' => self::RED,
                        'backgroundColor' => self::RED,
                        'order' => 3,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.results'),
                        'data' => $results,
                        'borderColor' => self::BLUE,
                        'backgroundColor' => self::BLUE,
                        'order' => 2,
                        'tension' => 0.25,
                        'fill' => false,
                        'animation' => true,
                    ],
                    [
                        'label' => $this->ts->trans('backend.admin.block.charts.zeros'),
                        'data' => $zeros,
                        'borderColor' => self::BLACK,
                        'backgroundColor' => self::BLACK,
                        'pointBorderWidth' => 0,
                        'pointStyle' => 'dash',
                        'borderWidth' => 2,
                        'borderDash' => [5, 5],
                        'order' => 1,
                        'tension' => 0,
                        'fill' => false,
                        'animation' => false,
                    ],
                ],
            ])
            ->setOptions([
                'aspectRatio' => 4,
                'scales' => [
                    'x' => [
                        'display' => true,
                    ],
                    'y' => [
                        'display' => true,
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
