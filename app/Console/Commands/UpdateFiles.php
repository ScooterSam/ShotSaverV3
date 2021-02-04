<?php

namespace App\Console\Commands;

use App\Jobs\TransferFile;
use App\Models\Files\File;
use App\Models\User;
use Arr;
use DB;
use Illuminate\Console\Command;
use Storage;
use Str;

class UpdateFiles extends Command
{
	protected $signature = 'files:update';

	protected $description = 'Command description';

	public function handle()
	{
		$this->migrateFiles();

	}

	private function migrateFiles()
	{

		$oldFiles = DB::connection('shotsaver1')
			->table('files')
			->get()
			->keyBy('name');


		$progress = $this->getOutput()->createProgressBar(File::count());
		$progress->start();

		File::query()
			->whereIn('name', Arr::pluck($oldFiles, 'name'))
			->chunk(100,
				/**
				 *
				 * @var File[] $files
				 */
				function ($files) use ($oldFiles, $progress) {

					foreach ($files as $file) {
						$oldFile = $oldFiles->get($file->name);

						if (!$oldFile) {
							$this->warn('Skipping file... NAME: ' . $file->name . ' cannot find it in old files list');
							$progress->advance();
							continue;
						}

						$extension = $oldFile->extension;

						if ($extension === null) {
							$extension = Str::afterLast($file->path, '.');
						}

						$file->update([
							'thumb'     => $oldFile->thumb,
							'extension' => $extension,
						]);

						$progress->advance();

					}

				});


		$progress->finish();
	}
}
