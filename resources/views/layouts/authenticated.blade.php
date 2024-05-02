<!-- resources/views/layouts/authenticated.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '預設標題')</title>
    <!-- 引入CSS和JavaScript -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>

        <!-- Page Content -->
        <div class="container flex flex-col sm:flex-row">
            @yield('content')
        </div>
    </div>
    {{-- <script>
        document.getElementById('start_at').addEventListener('change', function() {
            var start_at = new Date(this.value);
            console.log('start_at:', start_at);
            // 使用新的 start_at 值更新子視圖
            document.getElementById('startDate').textContent = start_at.toISOString().split('T')[0];
            document.getElementById('plusOneDay').textContent = new Date(start_at.getTime() + 1 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusThreeDays').textContent = new Date(start_at.getTime() + 3 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusSevenDays').textContent = new Date(start_at.getTime() + 7 * 24 * 60 * 60 *
                1000).toISOString().split('T')[0];
            document.getElementById('plusFourteenDays').textContent = new Date(start_at.getTime() + 14 * 24 * 60 *
                60 * 1000).toISOString().split('T')[0];
        });
    </script> --}}
</body>

</html>
