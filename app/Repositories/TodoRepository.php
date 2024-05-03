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

    function findTodo($id)
    {
        // return $this->transformTodoInData($this->todo->find($id));
        return $this->todo->find($id);
    }

    protected function transformTodoInData($todo)
    {
        // --- 把前端傳來的駝峰命名欄位轉為資料表的蛇形命名
        $todo['categoryId'] = $todo['category_id'];
        $todo['startAt'] = $todo['start_at']->format('Y-m-d');
        $todo['reminderTime'] = $todo['reminder_time']->format('H:i:s');
        $todo['dueAt'] = $todo['due_at']->format('Y-m-d');
        // --- 移除不需要的資料
        unset($todo['category_id'], $todo['start_at'], $todo['reminder_time'], $todo['due_at']);
        return $todo;
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
}