<div class="my-4" x-data="dietForm('{{ old('type', $todo['type'] ?? '') }}')"">
    <label class="formLabel">飲食目標：</label>
    <div class="grid grid-cols-9 gap-4 items-center">
        <select name="type" id="type" x-model="typeSelect" @if ($todo) disabled @endif
            @change="updateValues()" class="col-span-3 p-2 form-input">
            <option value="0"></option>
            <option value="1">減糖</option>
            <option value="2">多喝水</option>
            <option value="3">少油炸</option>
            <option value="4">多吃蔬果</option>
        </select>
  
        <div x-show="typeSelect != ''" class="col-span-6 flex flex-row">
            <span x-text="preText" class="p-2 min-w-14 text-sm font-medium text-gray-900 content-center text-center"></span>
            <input type="number" name="value" value="{{ old('value', $todo['value'] ?? '') }}" {{ $todo ? 'disabled' : '' }} class="form-input p-2 w-1/3" />
            <span x-text="units" class="p-2 min-w-14 text-sm font-medium text-gray-900 content-center text-center"></span>
        </div>
    </div>
  </div>
