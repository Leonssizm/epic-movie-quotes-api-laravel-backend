<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Quote::with('user', 'movie', 'comments')->get(), 200);
	}
}
