<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function setup()
    {
        return Inertia::render('App/Setup');
    }

    public function download(string $type)
    {
        if (!in_array($type, ['public', 'private'])) {
            throw ValidationException::withMessages(['type' => 'Invalid type specified, valid options are public, private.']);
        }

        $user  = auth()->user();
        $token = $user->createToken("Sharex Custom Uploader({$type})")->plainTextToken;

        $stub                         = json_decode(file_get_contents(resource_path('stub/sharex_config.stub.sxcu')));
        $stub->Headers->Authorization = "Bearer {$token}";
        $stub->Arguments->privacy     = $type;

        return response(json_encode($stub, JSON_PRETTY_PRINT), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="ShotSaver-Config-' . $type . '.sxcu"',
        ]);
    }
}
