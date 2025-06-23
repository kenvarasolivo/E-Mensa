@extends('layouts.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        header { background-color: #f4f4f4; padding: 10px; text-align: center; }
        footer { background-color: #ddd; padding: 10px; text-align: center; margin-top: 20px; }
        main { padding: 20px; }
    </style>
</head>
<body>
<header>
    @yield('header')
</header>
<main>
    @yield('main')
</main>
<footer>
    @section('footer')
        <p>footer</p>
    @show
</footer>
</body>
</html>
@endsection