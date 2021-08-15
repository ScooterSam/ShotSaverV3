<?php

namespace App\Http\Controllers;

use Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class AppController extends Controller
{
    public function landing()
    {
        return view('welcome');
    }

    public function sso(string $token)
    {
        if (!$token) {
            abort(401);
        }


        $redirect = request('redirect');
        if (!$redirect) {
            abort(404);
        }

        $user = Http::asJson()
            ->withToken($token)
            ->get(route('api.user.current'));

        $userId = $user->json('id');
        if (!$userId) {
            abort(401);
        }

        auth()->loginUsingId($userId);

        return response()->redirectTo($redirect);
    }
}
