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
    if($frequency == 1) {
      $instance['is_added'] = 1;
    }

    while ($instance['end_date'] < now()) {
      $instance['is_added'] = 1;
      $this->recurringInstance->create($instance);
      $instance['start_date'] = date('Y-m-d', strtotime($instance['end_date'] . self::DAY_INCREMENT ));
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
        return date('Y-m-d', strtotime($start_at . self::WEEK_INCREMENT ));
      case 4:
        return date('Y-m-d', strtotime($start_at . self::MONTH_INCREMENT ));
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

  public function findTodoMainAndRecurring($userId, $todoIds = [])
  {
    $query = $this->todo->select('id', 'title', 'category_id', 'introduction', 'frequency')
      ->where('user_id', $userId);

    if (!empty($todoIds)) {
      $query->with([
        'recurringInstance' => function ($query) use ($todoIds) {  // 使用 `use` 关键字引入 $userId
          $query->where('is_added', '=', 0)
          ->whereIn('todo_id', $todoIds);
      }
      ]);
    }

    $todos = $query->with([
      'studySpacedRepetitions',
      'studies',
      'sports',
      'diets',
      'routines',
    ])->get();

    $transformedTodos = $this->todoTransform($todos);
    return $transformedTodos;
  }

  private function todoTransform($todos)
  {
    return $todos->map(function ($todo) {
      $todo->category_id = $todo->category;
      $todo->frequency = $todo->frequencyType;
      $todo->displayText = $this->generateDisplayText($todo);
      return $todo;
    });
  }

  private static function generateDisplayText($todo)
  {
    switch ($todo->category_id) {
      case "一般學習法":
        return "{$todo->frequency} {$todo->studies[0]->value} {$todo->studies[0]->goalUnitToString}";
      case "運動":
        return "{$todo->frequency} {$todo->sports[0]->typeToString} {$todo->sports[0]->value} {$todo->sports[0]->goalUnitToString}";
      case "飲食":
        return "{$todo->frequency} {$todo->diets[0]->typeToString} {$todo->diets[0]->value}";
      case "作息":
        return self::generateRoutineText($todo->routines[0]);
      default:
        return "間隔學習法";
    }
  }

  private static function generateRoutineText($routine)
  {
    if (!$routine)
      return "未定義";

    $timeCondition = $valueOrTime = $actionText = "未定義";
    switch ($routine->type) {
      case "早起":
      case "早睡":
        $timeCondition = "早於";
        $valueOrTime = $routine->time;
        $actionText = $routine->type === "早睡" ? "睡覺" : "起床";
        break;
      case "區間":
        $timeCondition = "睡滿";
        $valueOrTime = $routine->value;
        $actionText = "小時";
        break;
    }
    return "{$timeCondition} {$valueOrTime} {$actionText}";
  }

  public function getRecordTodoIdsFromRecurringInstances()
  {
    return RecurringInstance::where('is_added', '=', 0)
      ->select('todo_id')
      ->pluck('todo_id')
      ->toArray();
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

    $formattedData = $instances->map(function ($instance) {
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

    return $formattedData;
  }
  public function recurringNowAll($userId)
  {
    $recordTodoIds = $this->getRecordTodoIdsFromRecurringInstances();

    if (!empty($recordTodoIds)) {
      $recordTodos = $this->findTodoMainAndRecurring($userId, $recordTodoIds);
    }

    return $recordTodos;
  }
}