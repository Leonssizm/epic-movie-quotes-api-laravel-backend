<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenreSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$genres = json_decode(File::get(database_path('data/genres.json')), true);

		foreach ($genres as $key => $genre) {
			Genre::create([
				'name' => [
					'en'=> $genre['en'],
					'ka'=> $genre['ka'],
				],
			]);
		}
	}
}
