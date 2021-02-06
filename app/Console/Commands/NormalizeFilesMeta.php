<?php

namespace App\Console\Commands;

use App\Models\Files\File;
use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Storage;

class NormalizeFilesMeta extends Command
{
	protected $signature = 'files:normalize-meta';

	protected $description = 'Command description';

	public function handle()
	{

		$query = File::query()
			->whereIn('type', ['image', 'video']);

		$progress = $this->getOutput()->createProgressBar($query->count());
		$progress->start();

		$query->chunk(100,
			/**
			 *
			 * @var File[] $files
			 */
			function ($files) use ($progress) {

				foreach ($files as $file) {

					$width  = null;
					$height = null;

					$visibility = $file->private ? 'private' : 'public';

					if (Storage::getVisibility($file->path) === $visibility) {
						Storage::setVisibility($file->path, $visibility);
						if ($file->thumb) {
							if (!Storage::exists($file->thumb) && $file->type == 'image') {
								$file->thumb = $file->path;
							} else {
								Storage::setVisibility($file->thumb, $visibility);
							}
						}
					}


					if (isset($file->meta['dimensions'])) {
						if (isset($file->meta['dimensions']['hd'])) {
							$width  = $file->meta['dimensions']['hd'][0];
							$height = $file->meta['dimensions']['hd'][1];
						} elseif (isset($file->meta['dimensions']['sd'])) {
							$width  = $file->meta['dimensions']['sd'][0];
							$height = $file->meta['dimensions']['sd'][1];
						}
					}


					if (!$width && !$height) {
						if ($file->type === 'image') {
							$image  = Image::make($file->getUrl());
							$width  = $image->getWidth();
							$height = $image->getHeight();
						}
						if ($file->type === 'video' && $file->thumb) {
							$image  = Image::make($file->getThumbUrl());
							$width  = $image->getWidth();
							$height = $image->getHeight();
						}

						if (!$width && !$height) {
							$this->line('Image ' . $file->name . ' skipped... cannot get dimensions.');

							$progress->advance();
							continue;
						}
					}

					$file->update([
						'meta' => [
							'width'  => $width,
							'height' => $height,
						],
					]);

					$progress->advance();

				}

			});


		$progress->finish();

	}
}
