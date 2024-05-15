<?php
namespace App\Repositories;

use App\Models\Diet;
use App\Models\Todo;
use App\Models\Sport;
use App\Models\Study;
use App\Models\Routine;
use App\Models\RecurringCheck;
use App\Models\RecurringInstance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\StudySpacedRepetition;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Charts\recurringChart;

class RecurringRepository
{
  const DAY_INCREMENT = "+1 day";
  const WEEK_INCREMENT = "+6 day";
  const MONTH_INCREMENT = "+30 day";

  function __construct(
    protected Todo $todo,
    protected Study $study,
    protected StudySpacedRepetition $studySpacedRepetition,
    protected Sport $sport,
    protected Diet $diet,
    protected Routine $routine,
    protected RecurringInstance $recurringInstance,
    protected RecurringCheck $recurringCheck,
  ) {
  }
  public function create($frequency, $start_at, $value, $todo_id)
  {
    $instance = [
      'todo_id' => $todo_id,
      'start_date' => $start_at,
      'goal_value' => $value ?? null,
      'end_date' => $this->calculateEndDate($frequency, $start_at),
    ];
    if ($frequency == 1) {
      $instance['is_added'] = 1;
    }

    while ($instance['end_date'] < now()) {
      $instance['is_added'] = 1;
      $this->recurringInstance->create($instance);
      $instance['start_date'] = date('Y-m-d', strtotime($instance['end_date'] . self::DAY_INCREMENT));
      $instance['end_date'] = $this->calculateEndDate($frequency, $instance['start_date']);
      $instance['is_added'] = 0;
    }
    $this->recurringInstance->create($instance);
  }

  private function calculateEndDate($frequency, $start_at)
  {
    switch ($frequency) {
      case 1:
      case 2:
        return $start_at;
      case 3:
        return date('Y-m-d', strtotime($start_at . self::WEEK_INCREMENT));
      case 4:
        return date('Y-m-d', strtotime($start_at . self::MONTH_INCREMENT));
    }
  }

  function update($value, $isCompleted, $recurringInstanceId)
  {
    $recurringInstance = RecurringInstance::find($recurringInstanceId);
    $recurringInstance->completed_value += $value;
    $recurringInstance->occurrence_status = $isCompleted;
    $recurringInstance->save();
  }

  function isOld($id)
  {
    $recurringInstance = RecurringInstance::find($id);
    $recurringInstance->is_added = 1;
    $recurringInstance->save();
  }

  public function fetchRecurringInstances($todoIds = [])
  {
    $query = $this->recurringInstance->orderBy('id', 'desc');

    if (!empty($todoIds)) {
        $query->whereIn('todo_id', $todoIds);
    }

    return $query->get();
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
    Log::info('begin Data: ' . $recurringInstance->start_date);
    Log::info('end Data: ' . $recurringInstance->end_date);
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

  public function needRenewInstances()
  {
    $instances = RecurringInstance::with([
      'Todo' => function ($query) {
        $query->with(['studies', 'sports', 'diets', 'routines']);
      }
    ])
      ->where('is_added', '=', 0)
      ->where('end_date', '<', now())
      ->get();

      return $instances->map(function ($instance) {
      $todo = $instance->Todo;
      $value = collect([
        $todo->studies->pluck('value'),
        $todo->sports->pluck('value'),
        $todo->diets->pluck('value'),
        $todo->routines->pluck('value')
      ])->reject(function ($values) {
        return $values->isEmpty();
      })->first();

      return [
        'recurring_instance_id' => $instance->id,
        'todo_id' => $todo->id,
        'frequency' => $todo->frequency,
        'end_date' => $instance->end_date->format('Y-m-d'),
        'value' => $value[0],
      ];
    });
  }
}