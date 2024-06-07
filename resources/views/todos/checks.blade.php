@extends('layouts.authenticated')

@section('title', '習慣紀錄')

@section('scripts')
    @vite(['resources/js/todoCheck.js'])
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        習慣紀錄
    </h2>
@endsection

@section('content')
    <div class="w-full bg-gray-100 flex flex-wrap p-2 sm:p-5">
        @foreach ($todos as $todo)
            <div id="isCompleted-{{ $todo->recurringInstances->id }}" data-value="{{ $todo->recurringInstances->occurrence_status }}"
                class="grid grid-cols-6 gap-1 shadow-xl border items-center border-gray-200 p-4 m-4 h-48 bg-white grow shadow-secondary-1 rounded-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <h5 class="col-start-1 col-end-7 mb-2 text-xl font-medium leading-tight">
                    {{ $todo->title }}
                </h5>
                <div class="col-start-1 col-end-7"> {{ $todo->category_id }}</div>
                <div class="col-start-1 col-end-4">目標</div>
                <div class="col-end-7 col-span-2">已完成</div>
                <div class="col-start-1 col-end-4" id="goalValue-{{ $todo->recurringInstances->id }}" data-value="{{ $todo->recurringInstances->goal_value }}"> {{ $todo->displayText }} </div>
                <div class="col-end-7 col-span-2" id="currentTotal-{{ $todo->recurringInstances->id }}" data-value="{{ $todo->recurringInstances->completed_value }}">{{ $todo->recurringInstances->completed_value }}</div>
                <div></div>
                {{-- Counter and Increment/Decrement Buttons --}}
                <button type="button" onclick="decrementValue({{ $todo->recurringInstances->id }})" id="decrement-button"
                    data-input-counter-decrement="counter-input"
                    class="flex-shrink-0 col-start-1 col-span-1 bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                    <svg class="w-2.5 h-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 18 2">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h16" />
                    </svg>
                </button>
                <input id="input-{{ $todo->recurringInstances->id }}" type="value" value="0"
                    class="flex-shrink-0 col-start-2 col-span-1 text-gray-900 border-0 bg-transparent text-sm font-normal focus:outline-none focus:ring-0 max-w-[2.5rem] px-0" />
                <button type="button" onclick="incrementValue({{ $todo->recurringInstances->id }})" id="increment-button"
                    data-input-counter-increment="counter-input"
                    class="flex-shrink-0 col-start-3 col-span-1 bg-gray-100 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                    <svg class="w-2.5 h-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d=" M9 1v16M1 9h16" />
                    </svg>
                </button>

                <button class="col-start-5 col-span-1" onclick="submitValue({{ $todo->recurringInstances->id }})">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endsection
