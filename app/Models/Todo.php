<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'category_id', 
        'label', 
        'title', 
        'introduction', 
        'frequency', 
        'note', 
        'start_at', 
        'due_at', 
        'reminder_time', 
        'status'
    ];

    protected $casts = [
        'start_at' => 'date:Y-m-d',
        'due_at' => 'date:Y-m-d',
        'reminder_time' => 'datetime:H:i:s',
    ];

    protected $categoryId = [
        1 => "間隔學習法",
        2 => "一般學習法",
        3 => "運動",
        4 => "飲食",
        5 => "作息",
    ];
    protected $frequencyType = [
        1 => "不重複",
        2 => "每天",
        3 => "每週",
        4 => "每月"
    ];
    
    protected function category():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->categoryId[$attributes['category_id']]
        );
    }
    protected function frequencyType(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => 
                isset($this->frequencyType[$attributes['frequency']]) ? $this->frequencyType[$attributes['frequency']] : '未設定'
        );
    }
    

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    public function recurringInstance(){
        return $this->hasMany(RecurringInstance::class);
    }

    public function studySpacedRepetitions() 
    {
        return $this->hasMany(StudySpacedRepetition::class);
    }
    public function studies() 
    {
        return $this->hasMany(Study::class);
    }
    public function sports() 
    {
        return $this->hasMany(Sport::class);
    }
    public function diets() 
    {
        return $this->hasMany(Diet::class);
    }
    public function routines() 
    {
        return $this->hasMany(Routine::class);
    }
}
