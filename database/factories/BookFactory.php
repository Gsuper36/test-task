<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        $authorId = $faker->randomElement(
            Author::pluck('id')
                ->toArray()
        );

        return [
            'name'        => $faker->name(),
            'description' => $faker->text(),
            'released_at' => $faker->date(),
            'author_id'   => $authorId
        ];
    }
}
