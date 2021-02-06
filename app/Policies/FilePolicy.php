<?php

namespace App\Policies;

use App\Models\Files\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the file.
	 *
	 * @param User|null $user
	 * @param File      $file
	 *
	 * @return Response
	 */
	public function view(?User $user, File $file): Response
	{
		if(!$file->private){
			return $this->allow();
		}

		if(!$user){
			return $this->deny('Authorisation required.');
		}

		if ($file->private && optional($user)->id !== $file->user_id) {
			return $this->deny('No permissions.');
		}

		return $this->allow();
	}

	/**
	 * Determine whether the user can create files.
	 *
	 * @param User $user
	 *
	 * @return bool
	 */
	public function create(User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can update the file.
	 *
	 * @param User $user
	 * @param File $file
	 *
	 * @return Response
	 */
	public function update(User $user, File $file): Response
	{
		if ($user->id !== $file->user_id) {
			return $this->deny('No permissions.');
		}

		return $this->allow();
	}

	/**
	 * Determine whether the user can delete the file.
	 *
	 * @param User $user
	 * @param File $file
	 *
	 * @return Response
	 */
	public function delete(User $user, File $file): Response
	{
		if ($user->id !== $file->user_id) {
			return $this->deny('No permissions.');
		}

		return $this->allow();
	}
}
