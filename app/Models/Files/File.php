<?php

namespace App\Models\Files;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
	use HasFactory, IsViewable, IsFavourable;

	protected $guarded = ['id'];

	protected $casts = [
		'meta' => 'json',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function views()
	{
		return $this->hasMany(FileView::class);
	}

	public function favourites()
	{
		return $this->hasMany(FileFavourite::class);
	}

	function formatSizeUnits(): string
	{
		$bytes = $this->size_in_bytes;

		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	/**
	 * Generate the url for this file.
	 *
	 * @return string
	 */
	public function getUrl(): string
	{
		$url = Storage::url($this->path);

		if ($this->private) {
			$url = Storage::temporaryUrl($this->path, now()->addMinutes(10));
		}

		return $url;
	}

}
