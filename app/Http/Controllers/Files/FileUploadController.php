<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Services\FileUpload\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FileUploadController extends Controller
{
    /**
     * Handle a file being uploaded from sharex on behalf of a user
     *
     * @return array|string|JsonResource|JsonResponse
     */
    public function upload(): array|string|JsonResource|JsonResponse
    {
        return FileUpload::forUser(auth()->user())->handle();
    }
}
