<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChartService;
use App\Services\CheckService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RecurringRepository;

class ChartController extends Controller
{
    function __construct(
        protected ChartService $chartService,
        protected RecurringRepository $recurringRepository
    ) {
    }
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = $this->chartService->chart($userId);
        return view('todos.charts', compact('todos'));
    }

    public function getChartData(Request $request, $recurringInstanceId)
    {
        $requestData = json_encode($request->json('instancesData'));
        $instancesData = json_decode($requestData);
        $currentIndex = $instancesData->currentIndex;  
        $recurringInstance = $instancesData->instances[$currentIndex];

        $chartData = [
            'labels' => [$this->chartService->createDateRange($recurringInstance)], // 示例
            'datasetsData' => [$this->chartService->getDailyChecks($recurringInstance)],
            'max' => $recurringInstance->goal_value,
        ];
        
        return response()->json($chartData);
    }
}
