<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class AppController extends Controller
{
	public function landing()
	{
		return Inertia::render('Welcome', [
			'canLogin'       => Route::has('login'),
			'canRegister'    => Route::has('register'),
		]);
	}
}
