<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'todo_id', 
        'type', 
        'value', 
        'goal_unit', 
    ];

    protected $unit = [
        1 => "次",
        2 => "小時",
        3 => "卡路里"
    ];

    protected $sportType = [
        1 => "跑步",
        2 => "單車騎行",
        3 => "散步",
        4 => "游泳",
        5 => "爬樓梯",
        6 => "健身",
        7 => "瑜伽",
        8 => "舞蹈",
        9 => "滑板",
        10 => "溜冰",
        11 => "滑雪",
        12 => "跳繩",
        13 => "高爾夫",
        14 => "網球",
        15 => "籃球",
        16 => "足球",
        17 => "排球",
        18 => "棒球",
        19=> "曲棍球",
        20 => "羽毛球",
        21 => "劍道",
        22 => "拳擊",
        23 => "柔道",
        24 => "跆拳道",
        25 => "柔術",
        26 => "舞劍",
        27 => "團體健身課程"
    ];
    
    protected function goalUnitToString():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->unit[$attributes['goal_unit']]
        );
    }
    protected function typeToString():Attribute
    {
        return Attribute::make(
            get:fn($value,$attributes)=>$this->sportType[$attributes['type']]
        );
    }
    public function todo() 
    {
        return $this->belongsTo(Todo::class,'todo_id');
    }
}
