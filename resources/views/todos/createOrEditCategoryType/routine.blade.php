<div class="my-4" x-data="routineForm('{{ old('type', $todo['type'] ?? '') }}')">
    <label class="formLabel">作息目標：</label>
    <div class="grid grid-cols-9 gap-4 items-center">
        <select name="type" id="type" x-model="typeSelect" @if ($todoId) disabled @endif
            @change="updateValues()" class="col-span-3 p-2 form-input">
            <option value="0"></option>
            <option value="1">早睡</option>
            <option value="2">早起</option>
            <option value="3">區間</option>
        </select>
        <div x-show="typeSelect != ''" class="col-span-6 flex flex-row">
            <span x-text="preText"
                class="p-2 min-w-14 text-sm font-medium text-gray-900 content-center text-center"></span>
            <template x-if="typeSelect != '3'">
                <input type="time" name="time" class="form-input p-2 w-1/3" />
            </template>
            <template x-if="typeSelect == '3'">
                <input type="number" name="value" class="form-input p-2 w-1/3" />
            </template>
            <span x-text="units"
                class="p-2 min-w-14 text-sm font-medium text-gray-900 content-center text-center"></span>
        </div>
    </div>
</div>