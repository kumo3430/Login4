<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        $count = $todos->count();
        return view('todos.index', compact('todos', 'count'));
    }

    public function show()
    {
        $todos = Todo::all();
        $count = $todos->count();
        return view('habits.index', compact('todos', 'count'));
    }
}
