<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Repositories\CheckRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RecurringRepository;
use App\Charts\recurringChart;

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

  function chart($userId)
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

    return $todos->map(function ($todo) use ($instancesByTodoId) {
      $todo->recurringInstances = $instancesByTodoId[$todo->id] ?? collect();
      $todo->chart = new RecurringChart();
      $todo->chart->setupChart($instancesByTodoId[$todo->id][0]);
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