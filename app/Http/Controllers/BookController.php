<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Exception;

class BookController extends Controller
{
    public function index()
    {
        return ApiResponseHelper::success(
            $this->books()
        );
    }

    private function books(): array
    {
        $data = [];

        foreach (Book::with('author')->get() as $book) {
            $data[] = $this->bookData($book);
        }

        return $data;
    }

    private function bookData(Book $book): array
    {
        return [
            'name'        => $book->name,
            'description' => $book->description,
            'author'      => $book->author->name,
            'released_at' => $book->released_at
        ];
    }

    public function create()
    {
        return ApiResponseHelper::success([
            'authors' => $this->authors()
        ]);
    }

    private function authors(): array
    {
        $data = [];

        foreach (Author::all() as $author) {
            $data[] = $this->authorData($author);
        }

        return $data;
    }

    private function authorData(Author $author): array
    {
        return [
            'name' => $author->name
        ];
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
        return ApiResponseHelper::success([
            'book'       => $book,
            'authors'    => $this->authors(),
            'subAuthors' => $this->subAuthors($book)
        ]); 
    }

    private function subAuthors(Book $book): array
    {
        $data = [];

        foreach (Author::where('id', '<>', $book->id) as $author) {
            $data[] = $this->authorData($author);
        }

        return $data;
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
