<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="filament-body">
    {{ $slot }}

    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>