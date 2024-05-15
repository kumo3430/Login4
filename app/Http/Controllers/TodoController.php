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

        $this->todoService->store($validated);

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
        $this->todoService->update($todo);
        
        return response()->json(['message' => 'Todo successfully edited'], 200);
    }
    public function destroy($id)
    {
        $this->todoService->destroy($id);
        return redirect()->back();
    }
}
