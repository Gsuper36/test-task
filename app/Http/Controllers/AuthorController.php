<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AuthorCreateRequest;
use App\Http\Requests\AuthorUpdateRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        return view(
            'dashboard.authors.list', 
            [
                'authors' => Author::all()
            ]
        );
    }

    public function create()
    {
        return view(
            'dashboard.authors.view', 
            [
                'author' => new Author
            ]
        );
    }

    public function store(AuthorCreateRequest $request)
    {
        (new Author)->fill($request->validated())
            ->save();

        return ApiResponseHelper::success();
    }

    public function edit(Author $author)
    {
        return view(
            'dashboard.authors.view',
            [
                'author' => $author
            ]
        );
    }

    public function update(AuthorUpdateRequest $request, Author $author)
    {
        $author->fill($request->validated())
            ->save();

        return ApiResponseHelper::success();
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return ApiResponseHelper::success();
    }
}
