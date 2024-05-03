<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RecurringRepository;

class TodoService
{
  function __construct(
    protected TodoRepository $todoRepository,
    protected RecurringRepository $recurringRepository,
    protected CategoryRepository $categoryRepository,
  ) {
  }

  function store($todo, $categoryItem)
  {
    $todo_id = $this->todoRepository->create($todo);

    $categoryItem['todo_id'] = $todo_id;
    $this->categoryRepository->create($todo['category_id'], $categoryItem);

    if ($todo['category_id'] != 1) {
      $this->recurringRepository->create($todo['frequency'], $todo['start_at'], $categoryItem, $todo_id);
    }
  }
}