<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookFilterRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Author;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

    public function filter(BookFilterRequest $request)
    {
        $query = Book::query();

        $this->addAuthorFilter($query, $request->author_id);
        $this->addAuthorTitleFilter($query, $request->author_title);
        $this->addBookTitleFilter($query, $request->book_title);
        $this->addSubAuthorFilter($query, $request->subathor_id);
        $this->addReleasedAtFilter($query, $request->released_at);

        $query->with('author')
            ->with('subAuthors');

        return ApiResponseHelper::success(
            $this->books($query->get())
        );
    }

    private function addAuthorFilter(Builder $query, ?int $authorId): void
    {
        if (! $authorId) {
            return;
        }

        $query->authorId($authorId);
    }

    private function addAuthorTitleFilter(Builder $query, ?string $authorTitle): void
    {
        if (! $authorTitle) {
            return;
        }

        $query->whereHas('author', function ($query) use ($authorTitle) {
            $query->where('author.name', 'like', "%{$authorTitle}%");
        });
    }

    private function addBookTitleFilter(Builder $query, ?string $bookTitle): void
    {
        if (! $bookTitle) {
            return;
        }

        $query->where('name', 'like', "%{$bookTitle}%");
    }

    private function addSubAuthorFilter(Builder $query, ?int $subAuthorId): void
    {
        if (! $subAuthorId) {
            return;
        }

        $query->whereHas('subAuthors', function ($query) use ($subAuthorId) {
            $query->where('id', '=', $subAuthorId);
        });
    }

    private function addReleasedAtFilter(Builder $query, $released_at): void
    {
        if (! $released_at) {
            return;
        }

        $released_at = Carbon::create($released_at);

        $query->where('released_at', '=', $released_at);
    }

    private function books(Collection $books): array
    {
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'name'        => $book->name,
                'author'      => $book->author->name,
                'description' => $book->description,
                'released_at' => $book->released_at,
                'subAuthors'  => $this->subAuthors($book)
            ];
        }

        return $data;
    }

    private function subAuthors(Book $book): array
    {
        $data = [];

        foreach ($book->subAuthors as $author) {
            $data[] = [
                'name' => $author->name
            ];
        }

        return $data;
    }
}
