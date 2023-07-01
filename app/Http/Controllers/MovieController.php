<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$perPage = $request->input('per_page', 6);

		$movies = Movie::orderByDesc('created_at')->paginate($perPage);

		return response()->json(new MovieCollection($movies), 200);
	}

	public function getAllUserMovies(User $user): JsonResponse
	{
		$movies = $user->movies()->get();

		if ($movies->isEmpty()) {
			return response()->json('User has no movies', 404);
		} else {
			return response()->json($movies, 200);
		}
	}

	public function getSingleMovie(Movie $movie): JsonResponse
	{
		$movie = new MovieResource($movie);
		return response()->json($movie, 200);
	}

	public function createMovie(StoreMovieRequest $request): JsonResponse
	{
		$validatedRequest = $request->validated();
		$movie = Movie::create([
			'title'       => $validatedRequest['title'],
			'release_year'=> $validatedRequest['release_year'],
			'director'    => $validatedRequest['director'],
			'description' => $validatedRequest['description'],
			'user_id'     => $validatedRequest['user_id'],
			'image'       => $this->storeImage($validatedRequest),
		]);

		$movie->genres()->attach($validatedRequest['genre_ids']);

		return response()->json($movie, 200);
	}

	public function editMovie(UpdateMovieRequest $request, Movie $movie)
	{
		$movie->update($request->validated());

		$movie->genres()->sync($request->validated()['genre_ids']);

		if ($request->has('image')) {
			if ($movie->image) {
				File::delete('storage/' . $movie->image);
			}
			$request->image = $this->storeImage($request);

			$movie->update([
				'image' => $request->image,
			]);

			return response()->json('success', 200);
		}
		return response()->json('success', 200);
	}

	public function deleteMovie(Movie $movie): JsonResponse
	{
		File::delete('storage/' . $movie->thumbnail);

		$movie->delete();

		return response()->json('Movie Removed', 204);
	}

	private function storeImage($request)
	{
		$storedImage = uniqid() . '-' . $request['title']['en'] . '.' . $request['image']->extension();
		$request['image']->move('storage', $storedImage);
		return $storedImage;
	}
}
