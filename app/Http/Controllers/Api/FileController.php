<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\Files\FileListResource;
use App\Http\Resources\Files\FileResource;
use App\Models\Files\File;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    /**
     * @return FileListResource
     */
    public function list(): FileListResource
    {
        return new FileListResource(
            FilesService::forUser(auth()->user())->files()
        );
    }

    public function favourites(): FileListResource
    {
        return new FileListResource(
            FilesService::forUser(auth()->user())->favourites()
        );
    }

    public function file(File $file): FileResource
    {
        $this->authorize('view', $file);

        $file->loadDefaults();

        $file->saveView();

        return new FileResource($file);
    }

    public function update(UpdateFileRequest $request, File $file)
    {
        $this->authorize('update', $file);

        $file->update($request->validated());
        $file->loadDefaults();

        return new FileResource($file);
    }
}
