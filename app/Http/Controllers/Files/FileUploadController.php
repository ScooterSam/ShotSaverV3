<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Files\File;
use App\Services\FileValidation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{

	/**
	 * Handle a file being upload from sharex on behalf of a user
	 *
	 * @return string
	 */
	public function upload(): string
	{
		$user = auth()->user();

		$file = request()->file('file');

		if (!$file) {
			return "No file uploaded.";
		}

		$directory = Str::random();
		$fileName  = Str::random() . '.' . $file->getClientOriginalExtension();

		$path = $file->storeAs($directory, $fileName, 'spaces');

		Storage::setVisibility($path, $user->private_uploads ? 'private' : 'public');

		$fileModel = $user->files()->save(new File([
			'name'          => $file->getClientOriginalName(),
			'type'          => resolve(FileValidation::class)->fileType($file->getMimeType()),
			'path'          => $path,
			'mime_type'     => $file->getMimeType(),
			'extension'     => $file->getClientOriginalExtension(),
			'size_in_bytes' => $file->getSize(),
			'private'       => $user->private_uploads,
			'status'        => 'complete',
		]));

		return route('files.view', $fileModel);
	}
}
