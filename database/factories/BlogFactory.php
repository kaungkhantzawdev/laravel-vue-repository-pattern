<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->name;
        $description = fake()->text(300);
        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'description' => $description,
            'excerpt' => Str::limit($description, 100, ' ...')
        ];
    }
}
