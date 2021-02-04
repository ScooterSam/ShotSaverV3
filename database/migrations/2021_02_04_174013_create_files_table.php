<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->index()->constrained()->cascadeOnDelete();
	        $table->string('name');
	        $table->longText('description')->nullable();
	        $table->string('type')->nullable();
	        $table->string('mime_type')->nullable();
	        $table->string('extension')->nullable();
	        $table->string('path');
	        $table->string('thumb')->nullable();
	        $table->bigInteger('size_in_bytes')->nullable();
	        $table->json('meta')->nullable();
	        $table->boolean('private')->default(true);
	        $table->enum('status', ['complete', 'processing', 'failed'])->default('processing');
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
        Schema::dropIfExists('files');
    }
}
