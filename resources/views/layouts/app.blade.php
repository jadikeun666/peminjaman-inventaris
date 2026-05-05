<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Peminjaman Inventaris')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>