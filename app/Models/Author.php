<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Author extends Model
{
    use HasFactory;

    protected $table = 'author';

    protected $fillable = [
        'title'
    ];

    public function books(): Relation
    {
        return $this->hasMany(
            Book::class,
            'author_id',
            'id'
        );
    }
}
