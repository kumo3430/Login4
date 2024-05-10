@extends('layouts.authenticated')

@section('title', '習慣紀錄')

@section('scripts')
    @vite(['resources/js/todoChart.js'])
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        習慣紀錄
    </h2>
@endsection

@section('content')
    <div class="w-full bg-gray-100 flex flex-wrap p-2 sm:p-5">
        @foreach ($todos as $todo)
            <div id="isCompleted-{{ $todo->recurringInstance[0]->id }}"
                data-value="{{ $todo->recurringInstance[0]->id }}"
                class="grid grid-cols-6 gap-1 shadow-xl border items-center border-gray-200 p-4 m-4 h-88 bg-white grow shadow-secondary-1 rounded-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <h5 class="col-start-1 col-end-7 mb-2 text-xl font-medium leading-tight">
                    {{ $todo->title }}
                </h5>
                <div class="col-start-1 col-end-7">
                    <div class="grid grid-rows-2 grid-cols-6 grid-flow-col gap-1">

                        <div class="col-start-1 col-end-3"> {{ $todo->category_id }}</div>
                        <div class="col-start-1 col-end-3" id="goalValue-{{ $todo->recurringInstance[0]->id }}"
                            data-value="{{ $todo->recurringInstance[0]->goal_value }}"> {{ $todo->displayText }} </div>
                        <button type="button" onclick="decrementValue({{ $todo->recurringInstance[0]->id }})" id="decrement-button"
                        class="row-span-2 col-span-1 inline-flex items-center justify-center">
                            <svg class="w-[34px] h-[34px] text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m14 8-4 4 4 4" />
                            </svg>

                        </button>
                        <div id="instances-{{ $todo->recurringInstance[0]->id }}" data-value="{{ $todo->recurringInstance }}"
                        class="row-span-1 col-span-2 inline-flex items-center justify-center">
                            {{ \Carbon\Carbon::parse($todo->recurringInstances[0]->start_date)->format('Y-m-d') }}
                            {{-- {{ \Carbon\Carbon::parse($todo->recurringInstances[0]->end_date)->format('Y-m-d') }} --}}
                        </div>
                        <div id="index-{{ $todo->recurringInstance[0]->id }}" data-value=0
                        class="row-span-1 col-span-2 inline-flex items-center justify-center">
                            {{-- {{ \Carbon\Carbon::parse($todo->recurringInstances[0]->start_date)->format('Y-m-d') }} --}}
                            {{ \Carbon\Carbon::parse($todo->recurringInstances[0]->end_date)->format('Y-m-d') }}
                        </div>
                        <button  type="button" onclick="incrementValue({{ $todo->recurringInstance[0]->id }})" id="increment-button"
                        class="row-span-2 col-span-1 inline-flex items-center justify-center">
                            <svg class="w-[34px] h-[34px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 16 4-4-4-4"/>
                              </svg>
                              
                        </button>
                    </div>
                </div>
                {{-- <div class="col-start-1 col-end-3"> {{ $todo->category_id }}</div>
                <div class="col-start-1 col-end-3" id="goalValue-{{ $todo->recurringInstance[0]->id }}"
                    data-value="{{ $todo->recurringInstance[0]->goal_value }}"> {{ $todo->displayText }} </div> --}}
                <div id="chart-{{ $todo->recurringInstance[0]->id }}" class="col-start-1 col-end-7">
                    {!! $todo->chart->container() !!}
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                    {!! $todo->chart->script() !!}

                </div>
            </div>
        @endforeach
    </div>
@endsection
