<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

    <style>
        .font-dejavu {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            font-family: 'tnr', 'Times New Roman', Times, serif !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    @stack('header')
    
    {{ $slot }}

    @stack('footer')
</body>

</html>
