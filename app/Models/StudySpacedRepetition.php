<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySpacedRepetition extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'todo_id', 
        'day1_date', 
        'day1_status', 
        'day3_date', 
        'day3_status', 
        'day7_date', 
        'day7_status', 
        'day14_date', 
        'day14_status', 
    ];

    protected $casts = [
        'day1_date' => 'date:Y-m-d',
        'day3_date' => 'date:Y-m-d',
        'day7_date' => 'date:Y-m-d',
        'day14_date' => 'date:Y-m-d',
    ];

    public function todo() 
    {
        return $this->belongsTo(Todo::class,'todo_id');
    }

}
