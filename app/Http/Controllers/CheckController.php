<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\CheckService;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RecurringRepository;

class CheckController extends Controller
{
    function __construct(
        protected CheckService $checkService,
        protected RecurringRepository $recurringRepository
    ) {
    }
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = $this->checkService->show($userId);
        return view('todos.checks', compact('todos'));
    }

    public function create(Request $request, $recurringInstanceId)
    {
        $value = (int) $request->value;
        $isCompleted = $request->isCompleted;

        $this->checkService->update($value, $isCompleted, $recurringInstanceId);

        $this->checkService->create($value, $recurringInstanceId);

        return response()->json(['message' => 'Todo record successfully!']);
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
