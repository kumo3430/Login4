<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Study extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'todo_id', 
        'value', 
        'goal_unit', 
    ];
    protected $unit = [
        1 => "次",
        2 => "小時"
    ];
    
    protected function goalUnitToString():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->unit[$attributes['goal_unit']]
        );
    }

    public function todo() 
    {
        return $this->belongsTo(Todo::class,'todo_id');
    }
}
