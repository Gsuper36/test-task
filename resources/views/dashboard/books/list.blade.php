@extends('layouts.app')

@section('content')

    <ul>
        @foreach ($books as $book)
            <li><a href="{{ route('books.edit', $book->id) }}"> {{ $book->name }}</a></li>
        @endforeach
    </ul>

    <a href="{{ route('books.create') }}">Добавить книгу</a>

@endsection
