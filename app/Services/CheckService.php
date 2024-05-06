<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Repositories\CheckRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RecurringRepository;

class CheckService
{
  function __construct(
    protected TodoRepository $todoRepository,
    protected RecurringRepository $recurringRepository,
    protected CategoryRepository $categoryRepository,
    protected CheckRepository $checkRepository,
  ) {
  }
  function show($userId)
  {
      return $this->recurringRepository->findTodoMainAndRecurring($userId);
  }

  function update($value, $isCompleted, $recurringInstanceId)
  {
      $this->recurringRepository->update($value, $isCompleted, $recurringInstanceId);
  }
  
  function create($value, $recurringInstanceId)
  {
    $this->checkRepository->create($value, $recurringInstanceId);

  }

  function edit($todoId)
  {
    $todo = $this->todoRepository->find($todoId);

    $categoryId = $todo['category_id'];
    $categoryItem = $this->categoryRepository->find($categoryId, $todoId);

    $todoAttributes = $todo->getAttributes();
    $categoryItemAttributes = $categoryItem->getAttributes();
    return array_merge($todoAttributes, $categoryItemAttributes);
  }


  function destroy($id)
  {
      $this->todoRepository->destroy($id);
  }
}