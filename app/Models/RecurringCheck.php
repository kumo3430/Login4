<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringCheck extends Model
{
    use HasFactory;
    protected $table = 'recurring_checks';    

    protected $fillable = [
        'instance_id',
        'check_date',
        'current_value',
        'sleep_time',
        'wake_up_time'
    ];

    protected $casts = [
        'check_datetime' => 'datetime:Y-m-d H:i:s',
        'sleep_time' => 'datetime:H:i:s',
        'wake_up_time' => 'datetime:H:i:s'
    ];


    public function recurringInstance(){
        return $this->belongsTo(RecurringInstance::class);
    }
}
