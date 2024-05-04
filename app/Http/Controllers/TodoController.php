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
        $todos = Todo::all();
        $count = $todos->count();
        return view('todos.index', compact('todos', 'count'));
    }

    function create()
    {
        $todo = null;
        return view('todos.createOrEdit', compact('todo'));
    }

    public function store(StoreTodoRequest $request)
    {
        $validated = $request->validated();

        // 抓取驗證過後的資料
        $todo = $validated['todo'];

        // 若習慣類別為作息的話習慣週期為每日
        if ($todo['category_id'] == 5) {
            $todo['frequency'] = 2;
        }
        
        $todo['user_id'] = Auth::user()->id;

        // 過濾所有 categoryItem 陣列中的 null 值
        $validated['categoryItem'] = array_filter($validated['categoryItem'], fn($value) => !is_null($value));
        $categoryItem = $validated['categoryItem'];

        $this->todoService->store($todo, $categoryItem);

        return redirect()->route('todos.todoList')->with('success', 'Todo successfully added');
    }

    function edit($id)
    {
        $todo = $this->todoService->edit($id);
        // dd(compact('todo'));
        return view('todos.createOrEdit', compact('todo'));
    }
    public function update(Request $request, $id)
    {
        // $user = User::findOrFail($id);
        // $user->update($request->all());

        // if ($request->ajax()) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'User updated successfully.',
        //         'data' => $user
        //     ]);
        // }
        return response()->json(['message' => 'Todo successfully added'], 200);
        // return back()->with('success', 'User updated successfully.');
    }


}
