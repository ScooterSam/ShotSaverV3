<?php


namespace App\Models\Files;


/**
 * Trait IsViewable
 *
 * @package App\Models\Files
 * @mixin File
 */
trait IsViewable
{

	/**
	 * Save a new view for the current person viewing this file
	 */
	public function saveView()
	{
		if ($this->views()->where('ip', request()->ip())->first()) {
			return;
		}

		$view     = new FileView;
		$view->ip = request()->ip();

		$this->views()->save($view);
	}
}
