<?php
namespace App\Repositories;

use App\Models\RecurringCheck;
use App\Models\RecurringInstance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\StudySpacedRepetition;

class CheckRepository
{
  function __construct(
    protected RecurringCheck $recurringCheck,
  ) {
  }
  public function create($value, $recurringInstanceId)
  {
    Log::info('record request:', ['value' => $value, 'recurringInstanceId' => $recurringInstanceId]);
    $check['current_value'] = $value;
    $check['instance_id'] = $recurringInstanceId;

    $this->recurringCheck->create($check);
  }
}