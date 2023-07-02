<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Like;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call(GenreSeeder::class);

		$users = User::factory(5)->create();

		$users->each(function ($user) {
			$movie = Movie::factory()->create(['user_id' => $user->id]);
			$quote = Quote::factory()->create(['user_id' => $user->id, 'movie_id' => $movie->id]);
			$comment = Comment::factory()->create(['user_id' => $user->id, 'quote_id' => $quote->id]);
			$like = Like::factory()->create(['user_id' => $user->id, 'quote_id' => $quote->id]);
			$genres = Genre::inRandomOrder()->limit(rand(1, 14))->get();

			$movie->genres()->attach($genres);
		});
	}
}
