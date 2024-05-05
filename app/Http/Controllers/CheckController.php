<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TodoService;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    function __construct(protected TodoService $todoService)
    {
    }
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = $this->todoService->show($userId);
        // dd($todos);
        return view('todos.checks', compact('todos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
