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
}