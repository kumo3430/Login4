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
    // 1. 更新
    $instances = $this->recurringRepository->needRenewInstances();
    // 2. 創建
    $this->processInstances($instances);
    // 3. 顯示
    return $this->recurringRepository->findTodoMainRecurring($userId);
  }

  private function processInstances($instances)
  {
      foreach ($instances as $instance) {
          $this->recurringRepository->isOld($instance['recurring_instance_id']);
          $startAt = date('Y-m-d', strtotime($instance['end_date'] . " +1 day"));
          $this->recurringRepository->create($instance['frequency'], $startAt, $instance['value'], $instance['todo_id']);
      }
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