<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view(
            'dashboard.books.index',
            [
                'books' => Book::all()
            ]
        );
    }

    public function create()
    {
        return view(
            'dashboard.books.view',
            [
                'book'    => new Book,
                'authors' => Author::all()
            ]
        );
    }

    public function addSubAuthor(Book $book, Author $author)
    {
        $book->subAuthors()->save($author);

        return redirect()->back();
    }

    public function store(Request $request)
    {
        
    }

    public function edit(Book $book)
    {
        return view(
            'dashboard.books.view',
            [
                'book'       => $book,
                'authors'    => Author::all(),
                'subauthors' => $book->subAuthors()->get()
            ]
        );
    }

    public function update(Request $request, Book $book)
    {
        
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->back();
    }
}
