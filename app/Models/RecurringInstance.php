<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringInstance extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'recurring_instances';

    protected $fillable = [
        'todo_id', 
        'start_date', 
        'end_date', 
        'completed_value', 
        'goal_value', 
        'occurrence_status', 
        'is_added'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function todo(){
        return $this->belongsTo(Todo::class);
    }

    public function recurringCheck(){
        return $this->hasMany(RecurringCheck::class);
    }
}
