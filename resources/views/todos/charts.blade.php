@extends('layouts.authenticated')

@section('title', '習慣紀錄')

@section('scripts')
    {{-- @vite(['resources/js/todoCheck.js']) --}}
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
                data-value="{{ $todo->recurringInstance[0]->occurrence_status }}"
                class="grid grid-cols-6 gap-1 shadow-xl border items-center border-gray-200 p-4 m-4 h-88 bg-white grow shadow-secondary-1 rounded-lg w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <h5 class="col-start-1 col-end-7 mb-2 text-xl font-medium leading-tight">
                    {{ $todo->title }}
                </h5>
                <div class="col-start-1 col-end-7"> {{ $todo->category_id }}</div>
                <div class="col-start-1 col-end-4" id="goalValue-{{ $todo->recurringInstance[0]->id }}"
                    data-value="{{ $todo->recurringInstance[0]->goal_value }}"> {{ $todo->displayText }} </div>
                <div class="col-start-1 col-end-7">
                    {{-- {!! $todo->chart->container() !!}
                    <script src="{{ $todo->chart->cdn() }}"></script>
                    {!! $todo->chart->script() !!} --}}

                    {!! $todo->chart->container() !!}
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                    {!! $todo->chart->script() !!}

                </div>
            </div>
        @endforeach
    </div>
@endsection
