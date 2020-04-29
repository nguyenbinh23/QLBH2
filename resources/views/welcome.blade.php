<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name','TUANMINHTECH') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2:wght@500&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <style>
            body {
                font-family: 'Baloo Tamma 2', cursive;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <main-app></main-app>
        </div>
        <script src={{ asset('js/app.js')}}></script>
        <script src="https://kit.fontawesome.com/7c0cca649a.js" crossorigin="anonymous"></script>
    </body>
</html>

