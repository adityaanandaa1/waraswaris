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

    {{-- CSS Custom Page --}}
    <link rel="stylesheet" href="{{ asset('css/dokter/layoutsmodal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/formrekammedis.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dokter/medicalrecords.css') }}">

    {{-- CSS Framework --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    @yield('content')
</body>
</html>