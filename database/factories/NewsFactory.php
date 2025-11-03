<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (News $news) {
            // Validate that the user has the 'author' role
            if ($news->user_id) {
                $user = User::find($news->user_id);
                if ($user && $user->role && $user->role->name !== 'author') {
                    throw new \Exception('Only users with author role can have news.');
                }
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Find a user with the 'author' role
        $author = User::whereHas('role', function ($query) {
            $query->where('name', 'author');
        })->inRandomOrder()->first();

        if (!$author) {
            throw new \Exception('No user with author role found. Please create at least one author before using NewsFactory.');
        }

        return [
            'title' => substr(fake()->sentence(10), 0, 100), // Max 100 characters
            'description' => substr(fake()->text(800), 0, 1024), // Max 1024 characters
            'content' => fake()->paragraphs(5, true),
            'user_id' => $author->id,
        ];
    }
}
