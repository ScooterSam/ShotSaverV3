<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileView extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

}
