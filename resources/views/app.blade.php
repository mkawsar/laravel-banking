<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Banking</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link type="text/css" href="//fonts.googleapis.com/css?family=Muli:400,300" rel="stylesheet">
    <link type="text/css" href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
{{--    <link type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ mix('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
<body>
<div id="app">
</div>

@if (app()->isLocal())
    <script src="{{ mix('js/app.js') }}"></script>
@else
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endif
</body>
</html>
