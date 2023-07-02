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

		return response()->json($movies, 200);
	}

	public function show(Movie $movie): JsonResponse
	{
		$movie = new MovieResource($movie);
		return response()->json($movie, 200);
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$validatedRequest = $request->validated();

		$imagePath = $this->storeImage($validatedRequest);
		$validatedRequest['image'] = $imagePath;

		$movie = Movie::create($validatedRequest);

		$movie->genres()->attach($validatedRequest['genre_ids']);

		return response()->json($movie, 200);
	}

	public function update(UpdateMovieRequest $request, Movie $movie)
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

	public function destroy(Movie $movie): JsonResponse
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
