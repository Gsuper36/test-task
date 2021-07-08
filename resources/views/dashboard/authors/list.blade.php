@extends('layouts.app')

@section('content')

    <ul>
        @foreach ($authors as $author)
            <li><a href="{{ route('authors.edit', $author->id) }}">{{ $author->name }}</a></li>
        @endforeach
    </ul>

    <a href="{{ route('authors.create') }}">Добавить автора</a>

@endsection
