<?php

namespace App\Http\Requests;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;


use function Laravel\Prompts\error;

class StoreTodoRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // 授權邏輯，這裡暫時返回 true
    }
    // TODO reminderTime dateFormat
    public function rules()
    {
        Log::info('Received data:', request()->all());
        return [
            'todo.id' => 'integer',
            'todo.category_id' => 'required|integer|min:1|max:5',
            'todo.title' => 'required|string|max:255',
            'todo.introduction' => 'required|string|max:255',
            'todo.label' => 'nullable|string|max:255',
            'todo.start_at' => 'required|date',
            // 'todo.due_at' => $this->dueAtRules(),
            'todo.due_at' => 'required|date',
            'todo.reminder_time' => 'required',
            'todo.frequency' => 'required|integer|min:1|max:4',
            'todo.note' => '',
            'categoryItem.value' => $this->valueRules(),
            'categoryItem.goal_unit' => $this->goalUnitRules(),
            'categoryItem.type' => $this->typeRules(),
            'categoryItem.time' => $this->timeRules(),
            'categoryItem.day1_date' => $this->spacedRules(),
            'categoryItem.day3_date' => $this->spacedRules(),
            'categoryItem.day7_date' => $this->spacedRules(),
            'categoryItem.day14_date' => $this->spacedRules(),
        ];
    }

    public function messages()
    {
        return [
            'todo.category_id.min' => '請選擇習慣類別',
            'todo.title.required' => '請填寫習慣標題',
            'todo.title.string' => '標題不能為特殊符號',
            'todo.title.max' => '標題太長了',
            'todo.introduction.required' => '請填寫習慣內容',
            'todo.introduction.string' => '內容不能為特殊符號',
            'todo.introduction.max' => '習慣內容太長了',
            'todo.start_at.required' => '請填寫開始日期',
            'todo.start_at.date' => '開始日期必須為日期時間',
            'todo.due_at.required' => '請填寫截止日期',
            'todo.due_at.date' => '截止日期必須為日期時間',
            'todo.reminder_time.required' => '請填寫提醒時間',
            'todo.reminder_time.date_format' => '請確認提醒時間格式',
            'todo.frequency.min' => '請填寫習慣頻率',

            'categoryItem.value.required' => '請輸入目標數值',
            'categoryItem.value.integer' => '目標數值只能為正整數',
            'categoryItem.value.min' => '目標數值必須大於0',
            'categoryItem.value.max' => '目標過大',
            'categoryItem.type.min' => '請選擇目標種類',
            'categoryItem.goal_unit.min' => '請選擇目標單位',
            'categoryItem.time.required' => '請選作息時間',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'todo' => [
                'category_id' => $this->category_id,
                'title' => $this->title,
                'introduction' => $this->introduction,
                'label' => $this->label,
                'start_at' => $this->start_at,
                'reminder_time' => $this->reminder_time,
                'frequency' => $this->frequency,
                'due_at' => ($this->category_id == 1 || $this->category_id == 5) ? $this->start_at : $this->due_at,
            ],
            'categoryItem' => [
                'value' => $this->value ?? null,
                'goal_unit' => $this->goal_unit ?? null,
                'type' => $this->type ?? null,
                'time' => $this->time ?? null,
                'day1_date' => ($this->category_id == 1) ? $this->calculateFutureDate($this->start_at, 1) : null,
                'day3_date' => ($this->category_id == 1) ? $this->calculateFutureDate($this->start_at, 3) : null,
                'day7_date' => ($this->category_id == 1) ? $this->calculateFutureDate($this->start_at, 7) : null,
                'day14_date' => ($this->category_id == 1) ? $this->calculateFutureDate($this->start_at, 14) : null,
            ]
        ]);
    }
    protected function calculateFutureDate($originalDate, $daysToAdd) {
        // 創建 DateTime 對象
        $date = new \DateTime($originalDate);
    
        // 增加天數
        $date->add(new \DateInterval('P' . $daysToAdd . 'D'));
    
        // 返回格式化的日期
        return $date->format('Y-m-d');
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $response = response()->json([
    //         'message' => 'The given data was invalid.',
    //         'errors' => $validator->errors()
    //     ], 422);

    //     throw new HttpResponseException($response);
    // }
    private function dueAtRules()
    {
        $categoryId = $this->get('category_id');
        if (in_array($categoryId, [2, 3, 4])) {
            return 'required|date';
        }
        return 'nullable';
    }

    private function valueRules()
    {
        $categoryId = $this->get('category_id');
        $itemType = $this->get('type');
        Log::info("Validating value with category_id: $categoryId and type: $itemType");
        if (in_array($categoryId, ['2', 3, 4])) {
            return 'required|integer|min:1';
        } else if (in_array($categoryId, [5]) && in_array($itemType, [3])) {
            return 'required|integer|min:1|max:12';
        }
        return 'nullable';
    }

    private function goalUnitRules()
    {
        $categoryId = $this->get('category_id');
        if (in_array($categoryId, [2, 3])) {
            return 'required|integer|min:1|max:3';
        }
        return 'nullable';
    }

    private function typeRules()
    {
        $categoryId = $this->get('category_id');
        if (in_array($categoryId, [3, 4, 5])) {
            return 'required|integer|min:1|max:3';
        }
        return 'nullable';
    }

    private function timeRules()
    {
        $categoryId = $this->get('category_id');
        $itemType = $this->get('categoryItem.type');
        if ($categoryId == 5 && in_array($itemType, [1, 2])) {
            return 'required|date_format:H:i:s';
        }
        return 'nullable';
    }

    private function spacedRules()
    {
        $categoryId = $this->get('category_id');
        if (in_array($categoryId, [1])) {
            return 'required|date';
        }
        return 'nullable';
    }
}
