<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
	public function changeLocale($locale): JsonResponse
	{
		if (in_array($locale, config('app.available_locales'))) {
			session()->put('lang', $locale);
		} else {
			session()->put('lang', 'en');
		}

		return response()->json('Language changed successfully', 200);
	}
}
