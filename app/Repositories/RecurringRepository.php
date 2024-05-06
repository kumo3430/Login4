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
  public function create($frequency, $start_at, $categoryItem, $todo_id)
  {
    $instance['todo_id'] = $todo_id;
    $instance['start_date'] = $start_at;

    if (isset($categoryItem['value']) && !is_null($categoryItem['value'])) {
      $instance['goal_value'] = $categoryItem['value'];
    } else {
      $instance['goal_value'] = null;
    }

    switch ($frequency) {
      case 1:
        $instance['end_date'] = $start_at;
        $instance['is_added'] = 1;
        break;
      case 2:
        $instance['end_date'] = $start_at;
        break;
      case 3:
        $instance['end_date'] = date('Y-m-d', strtotime($start_at . " +7 day"));
        break;
      case 4:
        $instance['end_date'] = date('Y-m-d', strtotime($start_at . " +30 day"));
        break;
    }
    $this->recurringInstance->create($instance);
  }

  function update($value, $isCompleted, $recurringInstanceId)
  {
    $recurringInstance = RecurringInstance::find($recurringInstanceId);
    Log::info('Updating completed value:', [
      'original' => $recurringInstance->completed_value,
      'increment' => $value,
      'new_value' => $recurringInstance->completed_value + $value
  ]);
    try {
      $recurringInstance->completed_value += $value;
      $recurringInstance->occurrence_status = $isCompleted;
      // $this->recurringInstance->find($recurringInstanceId)->update($recurringInstance);
      $recurringInstance->save();
  } catch (\Exception $e) {
      // 处理异常
      return response()->json(['error' => $e->getMessage()], 500);
  }
  }

  public function findTodoMainAndRecurring($userId, $todoIds = [])
  {
    $query = $this->todo->select('id', 'title', 'category_id', 'introduction', 'frequency')
      ->where('user_id', $userId);

    if (!empty($todoIds)) {
      $query->whereIn('id', $todoIds);
    }

    $todos = $query->with([
      'studySpacedRepetitions',
      'studies',
      'sports',
      'diets',
      'routines',
      'recurringInstance'
    ])->get();

    $transformedTodos = $this->todoTransform($todos);
    return $transformedTodos;
  }

  public function todoTransform($todos)
  {
    return $todos->map(function ($todo) {
      $todo->category_id = $todo->category;
      $todo->frequency = $todo->frequencyType;
      $todo->displayText = $this->generateDisplayText($todo);
      return $todo;
    });
  }

  public static function generateDisplayText($todo)
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

  public static function generateRoutineText($routine)
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
    return RecurringInstance::where('end_date', '>', now())
      ->select('todo_id')
      ->pluck('todo_id')
      ->toArray();
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