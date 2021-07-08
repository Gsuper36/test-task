<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view(
            'dashboard.books.list',
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

    public function addSubAuthor(Request $request)
    {
        $book   = Book::findOrFail($request->get('book_id'));
        $author = Author::findOrFail($request->get('author_id'));

        $book->subAuthors()->attach($author->id);

        return ApiResponseHelper::success();
    }

    public function store(BookCreateRequest $request)
    {
        (new Book)->fill($request->validated())
            ->save();

        return ApiResponseHelper::success();
    }

    public function edit(Book $book)
    {
        return view(
            'dashboard.books.view',
            [
                'book'       => $book,
                'authors'    => Author::all(),
                'subAuthors' => Author::where('id', '<>', $book->id)->get()
            ]
        );
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        $book->fill($request->validated())
            ->save();
        
        return ApiResponseHelper::success();
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return ApiResponseHelper::success();
    }
}
