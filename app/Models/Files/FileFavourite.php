<?php

namespace App\Models\Files;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFavourite extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}
}
