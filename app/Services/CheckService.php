<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Repositories\CheckRepository;
use App\Repositories\RecurringRepository;
use Illuminate\Support\Facades\Log;

class CheckService
{
  function __construct(
    protected TodoRepository $todoRepository,
    protected RecurringRepository $recurringRepository,
    protected CheckRepository $checkRepository,
  ) {
  }
  function show($userId)
  {
    $instances = $this->recurringRepository->needRenewInstances();
    $this->processInstances($instances);
    return $this->findTodoMainRecurring($userId);
  }

  private function processInstances($instances)
  {
    foreach ($instances as $instance) {
      $this->recurringRepository->isOld($instance['recurring_instance_id']);
      $startAt = date('Y-m-d', strtotime($instance['end_date'] . " +1 day"));
      $this->recurringRepository->create($instance['frequency'], $startAt, $instance['value'], $instance['todo_id']);
    }
  }

  public function findTodoMainRecurring($userId)
  {
    $recurringInstances = $this->recurringRepository->fetchRecurringInstances();
    $todoIds = $recurringInstances->pluck('todo_id')->unique();
    $todos = $this->todoRepository->fetchTodos($userId, $todoIds->all());

    return $this->mergeTodoWithRecurringInstances($todos, $recurringInstances);
  }

  protected function mergeTodoWithRecurringInstances($todos, $recurringInstances)
  {
    $instancesByTodoId = $recurringInstances->groupBy('todo_id');

    // 取得每個分組的第一筆資料
    $firstInstances = $instancesByTodoId->map(function ($instances) {
      return $instances->first();
    });

    return $todos->map(function ($todo) use ($firstInstances) {
      $todo->recurringInstances = $firstInstances[$todo->id] ?? collect();
      return $todo;
    });
  }
  function update($value, $isCompleted, $recurringInstanceId)
  {
    $this->recurringRepository->update($value, $isCompleted, $recurringInstanceId);
  }

  function create($value, $recurringInstanceId)
  {
    $this->checkRepository->create($value, $recurringInstanceId);
  }
}