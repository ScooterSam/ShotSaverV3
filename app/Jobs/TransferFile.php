<?php

namespace App\Jobs;

use App\Models\Files\File;
use App\Services\FileValidation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileExistsException;

class TransferFile implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $fileInformation;

	public function __construct($fileInformation)
	{
		$this->fileInformation = $fileInformation;
	}

	public function handle()
	{
		if (!Storage::disk('minio')->exists($this->fileInformation->name)) {
			$this->delete();

			return;
		}

		if (!Storage::disk('minio')->exists($this->fileInformation->hd)) {
			$this->delete();

			return;
		}

		$stream = Storage::disk('minio')->readStream($this->fileInformation->hd);

		try {
			Storage::disk('spaces')->writeStream($this->fileInformation->hd, $stream);
		} catch (FileExistsException $exception){
		}

		Storage::disk('spaces')->setVisibility(
			$this->fileInformation->hd,
			$this->fileInformation->private ? Filesystem::VISIBILITY_PRIVATE : Filesystem::VISIBILITY_PUBLIC
		);

		File::create([
			'id'            => $this->fileInformation->id,
			'user_id'       => $this->fileInformation->user_id,
			'name'          => $this->fileInformation->name,
			'type'          => $this->fileInformation->type,
			'path'          => $this->fileInformation->hd,
			'mime_type'     => $this->fileInformation->mime_type,
			'extension'     => $this->fileInformation->extension,
			'size_in_bytes' => $this->fileInformation->size_in_bytes,
			'private'       => $this->fileInformation->private,
			'status'        => $this->fileInformation->status,
			'meta'          => $this->fileInformation->meta ? json_decode($this->fileInformation->meta) : null,
		]);
	}
}
