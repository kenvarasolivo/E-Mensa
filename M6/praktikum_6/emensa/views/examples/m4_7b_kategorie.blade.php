@extends('layouts.layout')

@section('content')
<ul>
    @foreach($categories as $index => $category)
        <li class="{{ $index % 2 == 1 ? 'bold' : '' }}">
            {{ $category['name'] }}
        </li>
    @endforeach
</ul>
@endsection

@section('cssextra')
    <style> .bold{font-weight: bold}

    </style>
@endsection