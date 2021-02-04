<?php


namespace App\Models\Files;

/**
 * Trait IsFavourable
 *
 * @package App\Models\Files
 * @mixin File
 */
trait IsFavourable
{
	/**
	 * Allow the authed user to "favourite" a file
	 *
	 * @return bool
	 */
	public function favourite(): bool
	{
		$favourite = FileFavourite::where('user_id', auth()->id())
			->where('file_id', $this->id)
			->first();

		if ($favourite !== null) {
			$favourite->delete();

			return false;
		} else {
			$favourite          = new FileFavourite;
			$favourite->user_id = auth()->id();
			$favourite->file_id = $this->id;
			$favourite->save();

			return true;
		}
	}

	public function hasFavourited(): FileFavourite
	{
		return FileFavourite::where('user_id', auth()->id())
			->where('file_id', $this->id)
			->first();
	}
}
