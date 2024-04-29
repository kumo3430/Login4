<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diet extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'todo_id', 
        'type', 
        'value', 
    ];
    protected $dietType = [
        1 => "減糖",
        2 => "多喝水",
        3 => "少油炸",
        4 => "多吃蔬果"
    ];
    protected function typeToString():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->dietType[$attributes['type']]
        );
    }

    public function todo() 
    {
        return $this->belongsTo(Todo::class,'todo_id');
    }
}
