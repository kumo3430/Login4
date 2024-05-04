<div class="my-4">
    <label class="formLabel">學習目標：</label>
    <div class="grid grid-cols-2 gap-4">
        <input type="number" name="value" class="col-span-1 form-input" value="{{ old('value', $todo['value'] ?? '') }}"
            @if ($todoId) disabled @endif />
        <select name="goal_unit" id="goal_unit" class="col-span-1 form-input"
            @if ($todoId) disabled @endif>
            <option value="0" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 0 ? 'selected' : '' }}></option>
            <option value="1" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 1 ? 'selected' : '' }}>次</option>
            <option value="2" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 2 ? 'selected' : '' }}>小時</option>
        </select>
    </div>
</div>
