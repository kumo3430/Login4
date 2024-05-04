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
    <script>
        function dietForm(initialType) {
            console.log("Initial Type:", initialType);
            return {
                typeSelect: initialType,
                preText: '',
                units: '',
                init() {
                    this.updateValues();
                },
                updateValues() {
                    switch (this.typeSelect) {
                        case '1':
                            this.preText = '少于';
                            this.units = '次';
                            break;
                        case '2':
                            this.preText = '至少';
                            this.units = '豪升';
                            break;
                        case '3':
                            this.preText = '少于';
                            this.units = '次';
                            break;
                        case '4':
                            this.preText = '至少';
                            this.units = '份';
                            break;
                        default:
                            this.preText = '';
                            this.units = '';
                    }
                }
            };
        }
    </script>
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
</body>

</html>
