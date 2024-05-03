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

class CategoryRepository
{
  function __construct(
    protected Todo $todo,
    protected Study $study,
    protected StudySpacedRepetition $studySpacedRepetition,
    protected Sport $sport,
    protected Diet $diet,
    protected Routine $routine,
  ) {
  }

  function create($categoryId, $categoryItem)
  {
    switch ($categoryId) {
      case 1:
        $this->studySpacedRepetition->create($categoryItem);
        break;
      case 2:
        $this->study->create($categoryItem);
        break;
      case 3:
        $this->sport->create($categoryItem);
        break;
      case 4:
        $this->diet->create($categoryItem);
        break;
      case 5:
        $this->routine->create($categoryItem);
        break;
    }
  }

  function findCategoryItem($categoryId, $id)
  {
    $model = match ($categoryId) {
      '1' => $this->studySpacedRepetition,
      '2' => $this->study,
      '3' => $this->sport,
      '4' => $this->diet,
      '5' => $this->routine,
    };
    // return $this->transformCategoryData($model->where('todo_id', $id)->first(), $categoryId);
    return $model->where('todo_id', $id)->first();
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