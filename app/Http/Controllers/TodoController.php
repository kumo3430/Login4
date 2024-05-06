<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTodoRequest;

class TodoController extends Controller
{
    function __construct(protected TodoService $todoService)
    {
    }
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = $this->todoService->show($userId);
        // dd($todos);
        return view('todos.index', compact('todos'));
    }

    public function create()
    {
        $todo = null;
        return view('todos.createOrEdit', compact('todo'));
    }

    public function store(StoreTodoRequest $request)
    {
        $validated = $request->validated();

        // 抓取驗證過後的資料
        $todo = $validated['todo'];
        $todo['user_id'] = Auth::user()->id;

        // 過濾所有 categoryItem 陣列中的 null 值
        $validated['categoryItem'] = array_filter($validated['categoryItem'], fn($value) => !is_null($value));
        $categoryItem = $validated['categoryItem'];

        $this->todoService->store($todo, $categoryItem);

        return redirect()->route('todos.index')->with('success', 'Todo successfully added');
    }

    public function edit($id)
    {
        $todo = $this->todoService->edit($id);
        return view('todos.createOrEdit', compact('todo'));
    }

    public function update(StoreTodoRequest $request)
    {
        $validated = $request->validated();

        $todo = $validated['todo'];
        // $categoryItem = $validated['categoryItem'];
        $this->todoService->update($todo);
        return response()->json(['message' => 'Todo successfully edited'], 200);
    }
    public function destroy($id)
    {
        $this->todoService->destroy($id);
        return redirect()->back();
    }
}
