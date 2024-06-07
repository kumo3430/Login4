<?php
namespace App\Repositories;

use App\Models\Todo;
use App\Models\StudySpacedRepetition;
use App\Models\Study;
use App\Models\Sport;
use App\Models\Diet;
use App\Models\RecurringCheck;
use App\Models\RecurringInstance;
use App\Models\Routine;

class TodoRepository
{
    function __construct(
        protected Todo $todo,
       
    ) {
    }

    function create($todo)
    {
        return $this->todo->create($todo)->id;
    }

    function find($id)
    {
        return $this->todo->find($id);
    }

    function update($todo)
    {
        $id = $todo['id'];
        $this->todo->find($id)->update($todo);
    }

    function destroy($id)
    {
        $this->todo->find($id)->delete();
    }

    public function fetchTodos($userId, $todoIds = [])
    {
        $query = $this->todo->select('id', 'title', 'category_id', 'introduction', 'frequency')
            ->where('user_id', $userId);

        if (!empty($todoIds)) {
            $query->whereIn('id', $todoIds);
        }

        $todos = $query->with(['studySpacedRepetitions', 'studies', 'sports', 'diets', 'routines'])->get();

        return $this->transformTodos($todos);
    }

    protected function transformTodos($todos)
    {
        return $todos->map(function ($todo) {
            $todo->category_id = $todo->category;
            $todo->frequency = $todo->frequencyType;
            $todo->displayText = $this->generateDisplayText($todo);
            return $todo;
        });
    }

    private function generateDisplayText($todo)
    {
        switch ($todo->category_id) {
            case "一般學習法":
                return "{$todo->frequency} {$todo->studies[0]->value} {$todo->studies[0]->goalUnitToString}";
            case "運動":
                return "{$todo->frequency} {$todo->sports[0]->typeToString} {$todo->sports[0]->value} {$todo->sports[0]->goalUnitToString}";
            case "飲食":
                return "{$todo->frequency} {$todo->diets[0]->typeToString} {$todo->diets[0]->value} {$todo->diets[0]->goalUnitToString}";
            case "作息":
                return $this->generateRoutineText($todo->routines[0]);
            default:
                return "間隔學習法";
        }
    }

    private function generateRoutineText($routine)
    {
        if (!$routine) {
            return "未定義";
        }

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
}