<!-- resources/views/habits/index.blade.php -->
@extends('layouts.authenticated')

@section('title', '習慣列表')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        習慣列表
    </h2>
@endsection

@section('content')
    {{-- <div class="w-full bg-gray-100 flex flex-col sm:flex-row "> --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full border rounded-xl p-5 bg-white sm:p-8 sm:m-10">
        <table class="w-full text-sm text-left text-gray-500">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
            </div>

            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 sm:px-6">習慣名稱</th>
                    <th scope="col" class="hidden sm:table-cell sm:px-6 sm:py-3">
                        習慣類別
                    </th>
                    <th scope="col" class="hidden sm:table-cell sm:px-6 sm:py-3">
                        習慣內容
                    </th>
                    <th scope="col" class="py-3 sm:px-6">習慣目標</th>
                    <th scope="col" class="py-3 sm:px-6">編輯/刪除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todos as $index => $todo)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 sm:px-6">{{ $todo->title }}</td>
                        <!-- 其他數據 -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- </div> --}}
@endsection
