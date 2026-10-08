<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WarasWaris - Dokter')</title>

    {{-- Font Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Framework --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

    {{-- CSS Custom Page --}}
    <link rel="stylesheet" href="{{ asset('css/dokter/layouts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/antrean.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/daftarpasien.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/laporan.css') }}">
    
    {{-- CSS Custom Component --}}
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/monthpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/sidebar.css') }}">
</head>
<body>
    <div class="layout">
        <div class="sidebar">
            @include('partials.sidebar')
        </div>

        <div class="content ">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/todaydate.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="{{ asset('js/monthpicker.js') }}"></script>
</body>
</html>