<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Http\Resources\QuoteCollection;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$perPage = $request->input('per_page', 2);

		$quotes = Quote::orderByDesc('created_at')->paginate($perPage);

		return response()->json(new QuoteCollection($quotes), 200);
	}

	public function show(Quote $quote): JsonResponse
	{
		$quote = new QuoteResource($quote);
		return response()->json($quote, 200);
	}

	public function store(StoreQuoteRequest $request)
	{
		$validatedRequest = $request->validated();

		$imagePath = $this->storeImage($validatedRequest);
		$validatedRequest['thumbnail'] = $imagePath;

		$quote = Quote::create($validatedRequest);

		return response()->json($quote, 200);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote)
	{
		$quote->update($request->validated());

		if ($request->has('thumbnail')) {
			if ($quote->thumbnail) {
				File::delete('storage/' . $quote->thumbnail);
			}

			$request->thumbnail = $this->storeImage($request);

			$quote->update([
				'thumbnail' => $request->thumbnail,
			]);
			return response()->json('success', 200);
		}
		return response()->json('success', 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		File::delete('storage/' . $quote->thumbnail);

		$quote->delete();

		return response()->json('Quote Removed', 204);
	}

	private function storeImage($request)
	{
		$storedImage = uniqid() . '-' . $request['body']['en'] . '.' . $request['thumbnail']->extension();
		$request['thumbnail']->move('storage', $storedImage);
		return $storedImage;
	}
}
