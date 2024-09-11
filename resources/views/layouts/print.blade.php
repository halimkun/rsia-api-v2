<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

    @stack('styles')

    {{-- <style>
        @page {
            /* meaning top, right, bottom, left */
            margin: 100px 50px 50px 50pxp; /* Default margins for all pages */
        }

        @page :first {
            margin-top: 50px; /* Custom margin for the first page */
        }

        header { position: fixed; top: -10px; left: 0px; right: 0px; height: 50px; max-height: min-content !important; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    </style> --}}
    
</head>

<body class="font-serif antialiased">
    @stack('header')
    
    {{ $slot }}
</body>

</html>
