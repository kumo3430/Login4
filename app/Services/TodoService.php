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
  function show($userId)
  {
      return $this->recurringRepository->findTodoMainAndRecurring($userId);
  }


  function store($todo, $categoryItem)
  {
    $todo_id = $this->todoRepository->create($todo);

    $categoryItem['todo_id'] = $todo_id;
    $this->categoryRepository->create($todo['category_id'], $categoryItem);

    if ($todo['category_id'] != 1) {
      $this->recurringRepository->create($todo['frequency'], $todo['start_at'], $categoryItem['value'], $todo_id);
    }
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

  function update($todo)
  {
      $this->todoRepository->update($todo);
  }

  function destroy($id)
  {
      $this->todoRepository->destroy($id);
  }
}