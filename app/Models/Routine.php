<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Routine extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'todo_id', 
        'type', 
        'value', 
        'time', 
    ];

    protected $sleepType = [
        1 => "早睡",
        2 => "早起",
        3 => "區間"
    ];

    protected $casts = [
        'time' => 'datetime:H:i:s',
    ];

    protected function typeToString():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->sleepType[$attributes['type']]
        );
    }
    public function todo() 
    {
        return $this->belongsTo(Todo::class,'todo_id');
    }
}
