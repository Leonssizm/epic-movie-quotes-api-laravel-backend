<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$perPage = $request->input('per_page', 2);

		$quotes = Quote::with('user', 'movie', 'comments.user', 'likes')->paginate($perPage);

		return response()->json($quotes, 200);
	}
}
