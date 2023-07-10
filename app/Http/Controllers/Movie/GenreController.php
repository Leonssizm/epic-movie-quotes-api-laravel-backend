<?php

namespace App\Http\Controllers\Movie;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Genre::all(), 200);
	}
}
