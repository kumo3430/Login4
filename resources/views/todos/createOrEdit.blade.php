@extends('layouts.authenticated')

@section('title')
    {{ $todo ? '編輯習慣' : '新增習慣' }}
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $todo ? '編輯習慣' : '新增習慣' }}
    </h2>
@endsection

@section('content')
    <article x-data="{ showTutorial: false }" class="w-full text-wrap p-5 sm:w-1/4 sm:mt-10">
        <div class="font-medium flex items-center">
            @if (!$todo)
                <p>開始設立您的新習慣</p>
            @else
                <p>修改習慣注意事項</p>
            @endif
            <button @click="showTutorial = !showTutorial" class="p-2 sm:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path x-show="!showTutorial" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path x-show="!showTutorial" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path x-show="showTutorial" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21.0006 12.0007C19.2536 15.5766 15.8779 18 12 18M12 18C8.12204 18 4.7463 15.5766 2.99977 12.0002M12 18L12 21M19.4218 14.4218L21.4999 16.5M16.2304 16.9687L17.5 19.5M4.57812 14.4218L2.5 16.5M7.76953 16.9687L6.5 19.5" />
                </svg>
            </button>
        </div>
        @if (!$todo)
            <div class="text-gray-500 my-2"
                :class="{
                    'sm:block': true,
                    block: showTutorial,
                    hidden: !showTutorial,
                }">
                <p>
                    歡迎來到您的習慣建立平台，這裡提供完善的提醒及追蹤功能，協助您輕鬆管理日常習慣。不論您想增進健康、提升學習效率還是改善生活作息，確保您可以持續進步。
                </p>
                <li class="my-2">
                    <b>設定習慣：</b>快速新增您想要培養的習慣。
                </li>
                <li class="my-2">
                    <b>智能提醒：</b>根據您的需求定制提醒，不錯過任何關鍵時刻。
                </li>
                <li class="my-2">
                    <b>習慣追蹤：</b>實時查看您的進展，激勵自己持續前進。
                </li>
            </div>
        @else
            <div class="text-gray-500 my-2"
                :class="{
                    'sm:block': true,
                    block: showTutorial,
                    hidden: !showTutorial,
                }">
                <p>
                    為了維持習慣記錄的完整性和準確性，一旦設定了習慣，我們將不支持更改其內容。這樣做是為了確保每一項記錄都能真實反映您的行為模式，幫助您更好地理解和分析自己的習慣。如需調整您的目標或行為，建議新增一個習慣以適應新的需求。
                </p>
            </div>
        @endif
    </article>
    <div class="w-full border rounded-xl p-5 bg-white sm:w-3/4 sm:p-8 sm:m-10">
        <form x-data="{ selectedCategory: '{{ $todo['category_id'] ?? '' }}' }" action="{{ $todo ? route('todo.update', $todo['id']) : route('todo.store') }}"
            method="POST">
            @csrf
            @if ($todo)
                @method('PUT')
            @endif
            <!-- 表單內容 -->
            <div class="mt-3 my-6">
                <label for="category_id" class="formLabel">習慣類別：</label>
                <select id="category_id" name="category_id" {{ $todo ? 'disabled' : '' }} x-model="selectedCategory"
                    class="form-input" required>
                    <option value="">請選擇習慣類別</option>
                    <option value="1" {{ old('category_id', $todo['category_id'] ?? '') == 1 ? 'selected' : '' }}>間隔學習法
                    </option>
                    <option value="2" {{ old('category_id', $todo['category_id'] ?? '') == 2 ? 'selected' : '' }}>一般學習法
                    </option>
                    <option value="3" {{ old('category_id', $todo['category_id'] ?? '') == 3 ? 'selected' : '' }}>運動
                    </option>
                    <option value="4" {{ old('category_id', $todo['category_id'] ?? '') == 4 ? 'selected' : '' }}>飲食
                    </option>
                    <option value="5" {{ old('category_id', $todo['category_id'] ?? '') == 5 ? 'selected' : '' }}>作息
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label class="formLabel">習慣標題：</label>
                <input type="text" name="title" value="{{ old('title', $todo['title'] ?? '') }}" required
                    class="form-input" />
            </div>

            <div class="form-group">
                <label class="formLabel">習慣內容：</label>
                <input type="text" name="introduction" value="{{ old('introduction', $todo['introduction'] ?? '') }}"
                    required class="form-input" />
            </div>

            <div class="form-group">
                <label class="formLabel">習慣標籤：</label>
                <input type="text" name="label" value="{{ old('label', $todo['label'] ?? '') }}" class="form-input" />
            </div>

            <div class="form-group">
                <label class="formLabel">開始日期：</label>
                <input type="date" name="start_at" id="start_at"value="{{ old('start_at', $todo['start_at'] ?? '') }}"
                    required {{ $todo ? 'disabled' : '' }} class="form-input" />
            </div>

            <div class="form-group">
                <label for="" class="formLabel">提醒時間：</label>
                <input type="time" name="reminder_time" value="{{ old('reminder_time', $todo['reminder_time'] ?? '') }}"
                    required class="form-input" />
            </div>

            <template x-if="selectedCategory != '1'">
                <div>
                    <div class="form-group">
                        <label class="formLabel">習慣週期：</label>
                        <select name="frequency" id="frequency" {{ $todo ? 'disabled' : '' }} class="form-input">
                            <option value="0" {{ old('frequency', $todo['frequency'] ?? '') == 0 ? 'selected' : '' }}>
                            </option>
                            <option value="1" {{ old('frequency', $todo['frequency'] ?? '') == 1 ? 'selected' : '' }}>
                                不重複</option>
                            <option value="2" {{ old('frequency', $todo['frequency'] ?? '') == 2 ? 'selected' : '' }}>
                                每天</option>
                            <option value="3" {{ old('frequency', $todo['frequency'] ?? '') == 3 ? 'selected' : '' }}>
                                每週</option>
                            <option value="4" {{ old('frequency', $todo['frequency'] ?? '') == 4 ? 'selected' : '' }}>
                                每月</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label class="formLabel">結束日期：</label>
                        <input type="date" name="due_at" value="{{ old('due_at', $todo['due_at'] ?? '') }}"
                            class="form-input" />
                    </div>
                </div>

            </template>

            <template x-if="selectedCategory == '1'">
                <div id="sub_view">
                    @include('todos.createOrEditCategoryType.spaced', [
                        'start_at' => old('start_at', $todo['start_at'] ?? ''),
                    ])
                </div>
            </template>

            <template x-if="selectedCategory == '2'">
                <div>
                    @include('todos.createOrEditCategoryType.study', ['todoId' => $todo['id'] ?? ''])
                </div>
            </template>

            <template x-if="selectedCategory == '3'">
                @include('todos.createOrEditCategoryType.sport', ['todoId' => $todo['id'] ?? ''])
            </template>

            <template x-if="selectedCategory == '4'">
                @include('todos.createOrEditCategoryType.diet', ['todo' => $todo ?? ''])
            </template>

            <template x-if="selectedCategory == '5'">
                @include('todos.createOrEditCategoryType.routine', ['todoId' => $todo['id'] ?? ''])
            </template>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 提交按鈕 -->
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                    {{ $todo ? '更新' : '創建' }}
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('start_at').addEventListener('change', function() {
            var start_at = new Date(this.value);

            document.getElementById('plusOneDay').textContent = new Date(start_at.getTime() + 1 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusThreeDays').textContent = new Date(start_at.getTime() + 3 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusSevenDays').textContent = new Date(start_at.getTime() + 7 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusFourteenDays').textContent = new Date(start_at.getTime() + 14 * 24 * 60 *
                60 * 1000).toISOString().split('T')[0];
        });
    </script>
@endsection
