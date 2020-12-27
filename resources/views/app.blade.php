<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Preflight') }} @if (config()->has('app.title')) | {{ config('app.title', 'Made by Thavarshan') }} @endif</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Routes for JS -->
    @routes
</head>
<body class="text-gray-600 font-sans antialiased leading-normal">
    <div id="app" v-cloak>
        <!-- Page Content -->
        <router-view></router-view>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple></portal-target>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
