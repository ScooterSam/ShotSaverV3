<?php

use App\Models\User;
use App\Models\Files\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileFavouritesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_favourites', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(File::class)->index()->constrained()->cascadeOnDelete();
			$table->foreignIdFor(User::class)->index()->constrained()->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('file_favourites');
	}
}
