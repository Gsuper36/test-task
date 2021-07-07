<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Book extends Model
{
    use HasFactory;

    protected $table = 'book';

    protected $fillable = [
        'name',
        'author_id',
        'description',
        'released_at'
    ];

    public function author(): Relation
    {
        return $this->belongsTo(
            Author::class,
            'author_id',
            'id'
        );
    }

    public function subAuthors(): Relation
    {
        return $this->belongsToMany(
            Author::class,
            'sub_author',
            'book_id',
            'author_id'
        );
    }

    public function scopeAuthorId($query, $authorId)
    {
        $query->where('author_id', $authorId);
    }
}
