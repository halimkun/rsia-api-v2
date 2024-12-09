<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

    <style>
        .font-dejavu {
            font-family: 'DejaVu Sans', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased" style="font-family: 'Garamond', serif;">
    @stack('header')
    
    {{ $slot }}
</body>

</html>
