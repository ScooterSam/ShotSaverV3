<?php

namespace App\Console\Commands;

use App\Jobs\TransferFile;
use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Storage;

class MigrateFiles extends Command
{
	protected $signature = 'files:transfer';

	protected $description = 'Command description';

	public function handle()
	{
		$this->migrateUsers();

		$this->migrateFiles();

	}

	private function migrateUsers()
	{

		$users = DB::connection('shotsaver1')
			->table('users')
			->get();

		foreach ($users as $user) {
			User::create([
				'id'                        => $user->id,
				'name'                      => $user->name,
				'email'                     => $user->email,
				'email_verified_at'         => $user->email_verified_at,
				'password'                  => $user->password,
				'two_factor_secret'         => null,
				'two_factor_recovery_codes' => null,
				'remember_token'            => $user->remember_token,
				'profile_photo_path'        => null,
				'private_uploads'           => $user->private_uploads,
			]);
		}

	}

	private function migrateFiles()
	{

		$files = DB::connection('shotsaver1')
			->table('files')
			->get();

		//$transferring = 0;

		$progress = $this->getOutput()->createProgressBar(count($files));
		$progress->start();

		foreach ($files as $file) {
			TransferFile::dispatch($file);

			//$transferring++;

			$progress->advance();

			// if ($transferring > 10) {
			// 	$progress->finish();
			// 	break;
			// }
		}
		$progress->finish();
	}
}
