<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    function __construct(protected CheckService $checkService)
    {
    }
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = $this->checkService->show($userId);
        // dd($todos);
        return view('todos.checks', compact('todos'));
    }

    // 在 TodoController 中
    public function record(Request $request, $recurringInstanceId)
    {

        // 1. instances update completed_value occurrence_status
        $value = (int) $request->value;
        $isCompleted = $request->isCompleted;
        Log::info('record request:', ['value' => $value, 'isCompleted' => $isCompleted]);
        $this->checkService->update($value, $isCompleted, $recurringInstanceId);

        // 2. check create instance_id current_value time

        $this->checkService->create($value, $recurringInstanceId);

        return response()->json(['message' => 'Todo updated successfully!']);
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
