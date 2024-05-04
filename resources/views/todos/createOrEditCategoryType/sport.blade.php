<div>
    <div class="my-4">
        <label class="formLabel">運動目標：</label>
        <div class="grid grid-cols-9 gap-4">
            <select name="type" id="type" @if ($todoId) disabled @endif
                class="col-span-4 p-2 form-input">
                <option value="0" {{ old('type', $todo['type'] ?? '') == 0 ? 'selected' : '' }}></option>
                @for ($i = 1; $i <= 27; $i++)
                    <option value="{{ $i }}" {{ old('type', $todo['type'] ?? '') == $i ? 'selected' : '' }}>
                        @switch($i)
                            @case(1)
                                跑步
                            @break

                            @case(2)
                                單車騎行
                            @break

                            @case(3)
                                散步
                            @break

                            @case(4)
                                游泳
                            @break

                            @case(5)
                                爬樓梯
                            @break

                            @case(6)
                                健身
                            @break

                            @case(7)
                                瑜伽
                            @break

                            @case(8)
                                舞蹈
                            @break

                            @case(9)
                                滑板
                            @break

                            @case(10)
                                溜冰
                            @break

                            @case(11)
                                滑雪
                            @break

                            @case(12)
                                跳繩
                            @break

                            @case(13)
                                高爾夫
                            @break

                            @case(14)
                                網球
                            @break

                            @case(15)
                                籃球
                            @break

                            @case(16)
                                足球
                            @break

                            @case(17)
                                排球
                            @break

                            @case(18)
                                棒球
                            @break

                            @case(19)
                                曲棍球
                            @break

                            @case(20)
                                羽毛球
                            @break

                            @case(21)
                                劍道
                            @break

                            @case(22)
                                拳擊
                            @break

                            @case(23)
                                柔道
                            @break

                            @case(24)
                                跆拳道
                            @break

                            @case(25)
                                柔術
                            @break

                            @case(26)
                                舞劍
                            @break

                            @case(27)
                                團體健身課程
                            @break
                        @endswitch
                    </option>
                @endfor
            </select>
            <input type="number" name="value" class="col-span-2 p-2 form-input"
                value="{{ old('value', $todo['value'] ?? '') }}" @if ($todoId) disabled @endif />
            <select name="goal_unit" id="goal_unit" @if ($todoId) disabled @endif
                class="p-2 col-span-3 form-input">
                <option value="0" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 0 ? 'selected' : '' }}></option>
                <option value="1" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 1 ? 'selected' : '' }}>次
                </option>
                <option value="2" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 2 ? 'selected' : '' }}>小時
                </option>
                <option value="3" {{ old('goal_unit', $todo['goal_unit'] ?? '') == 3 ? 'selected' : '' }}>卡路里
                </option>
            </select>
        </div>
    </div>
</div>
