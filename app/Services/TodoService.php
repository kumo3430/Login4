<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use Illuminate\Support\Facades\Auth;
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
    // return  $this->recurringRepository->fetchTodos($userId);
    return  $this->todoRepository->fetchTodos($userId);
  }


  function store($validated)
  {
    $todo = $validated['todo'];
    $todo['user_id'] = Auth::user()->id;
    $todo_id = $this->todoRepository->create($todo);

    $categoryItem['todo_id'] = $todo_id;
    // 過濾所有 categoryItem 陣列中的 null 值
    $validated['categoryItem'] = array_filter($validated['categoryItem'], fn($value) => !is_null($value));
    $categoryItem = $validated['categoryItem'];
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