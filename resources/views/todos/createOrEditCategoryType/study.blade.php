<div class="my-4">
    <label class="formLabel">學習目標：</label>
    <div class="grid grid-cols-2 gap-4">
        <input type="number" name="value" class="col-span-1 form-input"  @if($todoId) disabled @endif />
        <select name="goal_unit" id="goal_unit"  @if($todoId) disabled @endif class="col-span-1 form-input">
            <option value="0"></option>
            <option value="1">次</option>
            <option value="2">小時</option>
        </select>
    </div>
</div>
