<?php

namespace App\Jobs\Files;

use App\Models\Files\File;
use App\Services\ImageResolutionFilter;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileExistsException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Storage;

class CreateThumbnail implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var File
	 */
	public $file;
	public $folderName;

	public function __construct($folderName, File $file)
	{
		$this->file       = $file;
		$this->folderName = $folderName;
	}

	public function handle()
	{
		$thumbnailBasicPath = 'temporary/' . $this->folderName . '/thumbnail.jpeg';
		$thumbnailFullPath  = storage_path('app/' . $thumbnailBasicPath);
		$filePath           = storage_path('app/temporary/' . $this->folderName . '/' . Str::afterLast($this->file->path, '/'));


		if ($this->file->type === 'video') {
			$this->extractFromVideo($filePath, $thumbnailFullPath, $thumbnailBasicPath);
		}
		if ($this->file->type === 'image') {
			$this->extractFromImage($filePath, $thumbnailFullPath, $thumbnailBasicPath);
		}

		Storage::disk('local')->deleteDirectory('temporary/' . $this->folderName);


	}

	/**
	 * Extract a frame from the video and lower it's resolution so we can display a thumbnail of this video
	 *
	 *
	 * @param string $videoPath
	 * @param string $thumbnail
	 * @param string $location
	 *
	 */
	public function extractFromVideo(string $videoPath, string $thumbnail, string $location): void
	{
		$ffmpeg = FFMpeg::create();
		$video  = $ffmpeg->open($videoPath);
		$frame  = $video->frame(TimeCode::fromSeconds(1));
		$frame->addFilter(new ImageResolutionFilter());
		$frame->save($thumbnail);

		$width  = null;
		$height = null;

		$stream = $frame->getVideo()->getStreams()->first();

		if ($stream && $stream->isVideO()) {
			$dimensions = $stream->getDimensions();

			$width  = $dimensions->getWidth();
			$height = $dimensions->getHeight();
		}

		$this->storeThumbnail($width, $height);
	}

	/**
	 * Create a lower resolution/quality version of the image so we're not displaying the full size file in the list and such
	 *
	 * @param string $filePath
	 * @param string $thumbnailFullPath
	 * @param string $thumbnailBasicPath
	 *
	 * @throws FileExistsException
	 * @throws FileNotFoundException
	 */
	public function extractFromImage(string $filePath, string $thumbnailFullPath, string $thumbnailBasicPath): void
	{
		$image = Image::make($filePath);

		$image->resize(640, null, function ($constraint) {
			$constraint->aspectRatio();
		});

		$image->save($thumbnailFullPath, 70, 'jpeg');


		$this->storeThumbnail($image->width(), $image->height());

	}

	public function storeThumbnail($width, $height)
	{
		$folderName = Str::beforeLast($this->file->path, '/');

		$this->file->meta  = [
			'width'  => $width,
			'height' => $height,
		];
		$this->file->thumb = $folderName . '/thumbnail.jpeg';
		$this->file->save();

		$stream = Storage::disk('local')->readStream('temporary/' . $this->folderName . '/thumbnail.jpeg');

		Storage::disk('spaces')->writeStream($folderName . '/thumbnail.jpeg', $stream);

		Storage::setVisibility($folderName . '/thumbnail.jpeg', $this->file->private ? 'private' : 'public');
	}

}
