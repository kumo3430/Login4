<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class RecurringChart extends Chart
{
    public function __construct()
    {
        parent::__construct();
        $this->options([
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ],
            'legend' => [
                'display' => false
            ]
        ]);
    }

    public function setupChart($recurringInstance)
    {
        parent::options([
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'min' => 0,
                            'max' => $recurringInstance->goal_value,
                            'stepSize' => 1
                        ]
                    ]
                ]
            ]
        ]);
        $this->labels($this->createDateRange($recurringInstance));
        $this->dataset('', 'line', $this->getDailyChecks($recurringInstance))
            ->linetension(0.1);
    }

    private function createDateRange($recurringInstance, $format = "Y-m-d")
    {
        $begin = new \DateTime($recurringInstance->start_date);
        $end = new \DateTime($recurringInstance->end_date);
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }

        // 添加结束日期
        $lastDate = end($range);
        if ($lastDate !== $end->format($format)) {
            $range[] = $end->format($format);
        }
        // dd($range);
        return $range;
    }

    private function getDailyChecks($recurringInstance)
    {
        $dates = $this->createDateRange($recurringInstance);
        $checks = DB::table('recurring_checks')
            ->select(DB::raw('DATE_FORMAT(check_datetime, "%Y-%m-%d") as formatted_date'), DB::raw('SUM(current_value) as total_value'))
            ->where('instance_id', $recurringInstance->id)
            ->whereIn(DB::raw('DATE(check_datetime)'), $dates)
            ->groupBy('formatted_date')
            ->pluck('total_value', 'formatted_date')
            ->toArray();

        $dailyChecks = [];
        foreach ($dates as $date) {
            $dailyChecks[] = $checks[$date] ?? 0;
        }

        return $dailyChecks;
    }
}
