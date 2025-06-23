@extends('layouts.layout')

@section('content')
    @if ($message)
        <p>{{ $message }}</p>
    @else
        <ul>
            @foreach ($gerichte as $gericht)
                <li>{{ htmlspecialchars($gericht['name']) }} - {{ $gericht['preisintern'] }}€</li>
            @endforeach
        </ul>
    @endif
@endsection