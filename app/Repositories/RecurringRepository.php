<?php
namespace App\Repositories;

use App\Models\Diet;
use App\Models\Todo;
use App\Models\Sport;
use App\Models\Study;
use App\Models\Routine;
use App\Models\RecurringCheck;
use App\Models\RecurringInstance;
use Illuminate\Support\Facades\Log;
use App\Models\StudySpacedRepetition;

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