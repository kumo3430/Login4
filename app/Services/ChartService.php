<?php

namespace App\Services;

use App\Charts\recurringChart;
use Illuminate\Support\Facades\DB;
use App\Repositories\TodoRepository;
use App\Repositories\CheckRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RecurringRepository;

class ChartService
{
  function __construct(
    protected TodoRepository $todoRepository,
    protected RecurringRepository $recurringRepository,
    protected CheckRepository $checkRepository,
  ) {
  }

  function chart($userId)
  {
    $instances = $this->recurringRepository->needRenewInstances();
    $this->processInstances($instances);
    return $this->findTodoMainRecurring($userId);
  }

  private function processInstances($instances)
  {
    foreach ($instances as $instance) {
      $this->recurringRepository->isOld($instance['recurring_instance_id']);
      $startAt = date('Y-m-d', strtotime($instance['end_date'] . " +1 day"));
      $this->recurringRepository->create($instance['frequency'], $startAt, $instance['value'], $instance['todo_id']);
    }
  }

  public function findTodoMainRecurring($userId)
  {
    $recurringInstances = $this->recurringRepository->fetchRecurringInstances();
    $todoIds = $recurringInstances->pluck('todo_id')->unique();
    $todos = $this->todoRepository->fetchTodos($userId, $todoIds->all());

    return $this->mergeTodoWithRecurringInstances($todos, $recurringInstances);
  }

  protected function mergeTodoWithRecurringInstances($todos, $recurringInstances)
  {
    $instancesByTodoId = $recurringInstances->groupBy('todo_id');

    return $todos->map(function ($todo) use ($instancesByTodoId) {
      $todo->recurringInstances = $instancesByTodoId[$todo->id] ?? collect();
      $todo->chart = new RecurringChart();
      $todo->chart->setupChart($instancesByTodoId[$todo->id][0]);
      return $todo;
    });
  }
  public function getDailyChecks($recurringInstance)
  {
    $dates = $this->createDateRange($recurringInstance);

    $checks = DB::table('recurring_checks')
      ->select(DB::raw('DATE_FORMAT(check_datetime, "' . '%Y-%m-%d' . '") as formatted_date'), DB::raw('SUM(current_value) as total_value'))
      ->where('instance_id', $recurringInstance->id)
      ->whereIn(DB::raw('DATE(check_datetime)'), $dates) // 這裡使用 DATE() 來保證日期格式一致
      ->groupBy('formatted_date')
      ->pluck('total_value', 'formatted_date')
      ->toArray();

    // 確保每一天都有數據，沒有的話填充為0
    $dailyChecks = [];
    foreach ($dates as $date) {
      $dailyChecks[] = isset($checks[$date]) ? $checks[$date] : 0;
    }
    return array_map('intval', $dailyChecks);
  }

  public function createDateRange($recurringInstance, $format = "Y-m-d")
  {
    $begin = new \DateTime($recurringInstance->start_date);
    $end = new \DateTime($recurringInstance->end_date);
    $end->modify('+1 day'); // 包含最後一天
    $interval = new \DateInterval('P1D'); // 1 Day
    $dateRange = new \DatePeriod($begin, $interval, $end);

    $range = [];
    foreach ($dateRange as $date) {
      $range[] = $date->format($format);
    }
    return $range;
  }
}