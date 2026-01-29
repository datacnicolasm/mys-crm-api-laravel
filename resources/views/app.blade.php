<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Preamble de React Refresh para HMR (solo en dev) -->
        @viteReactRefresh

        <!-- Styles / Scripts -->
        @vite('resources/ts/main.tsx')
    </head>
    <body class="antialiased">
        <div id="root"></div>
    </body>
</html>