<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Files\File;
use DigitalOceanV2\HttpClient\Util\JsonObject;
use GrahamCampbell\DigitalOcean\DigitalOceanManager;
use GrahamCampbell\DigitalOcean\Facades\DigitalOcean;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Storage;

class FileController extends Controller
{
	/**
	 * @param false $favourites
	 *
	 * @return JsonResponse|Response
	 */
	private function filesList(bool $favourites)
	{
		$user = auth()->user();

		if (request()->has('order_by')) {
			if (!in_array(request('order_by'), ['created', 'size', 'type', 'views', 'favourites'])) {
				return response()->json(['message' => 'Invalid order...'], 500);
			}
		}
		if (request()->has('order')) {
			if (!in_array(request('order'), ['desc', 'asc'])) {
				return response()->json(['message' => 'Invalid order...'], 500);
			}
		}

		$files = $user->files();

		if ($favourites) {
			$files = $user->favouriteFiles();
		}

		$files = $files
			->withCount('views as views')
			->withCount('favourites as total_favourites')
			->withCount([
				'favourites as favourited' => function ($query) use ($user) {
					$query->where('user_id', $user->id);
				},
			])
			->when(request()->has('order_by'), function ($query) {
				$orderBy = request('order_by');
				$order   = request('order');

				if ($orderBy === 'created') {
					return $query->orderBy('created_at', $order);
				}
				if ($orderBy === 'size') {
					return $query->orderBy('size_in_bytes', $order);
				}
				if ($orderBy === 'type') {
					return $query->orderBy('type', $order);
				}
				if ($orderBy === 'views') {
					return $query->orderBy('views', $order);
				}
				if ($orderBy === 'favourites') {
					return $query->orderBy('total_favourites', $order);
				}
			}, function ($query) {
				return $query->orderBy('id', request('order', 'desc'));
			})
			->paginate(10);

		return Inertia::render('Files/List', [
			'files'         => FileResource::collection($files),
			'is_favourites' => $favourites,
		]);
	}

	/**
	 * Show a filterable list of the users files
	 *
	 * @return JsonResponse|Response
	 */
	public function list()
	{
		return $this->filesList(false);
	}

	/**
	 * Show a filterable list of the users favourite files
	 *
	 * @return JsonResponse|Response
	 */
	public function favourites()
	{
		return $this->filesList(true);
	}

	/**
	 * View an uploaded file
	 *
	 * @param File $file
	 *
	 * @return Response
	 * @throws AuthorizationException
	 */
	public function view(File $file): Response
	{
		$this->authorize('view', $file);

		$file
			->load('user')
			->loadCount('views as views')
			->loadCount('favourites as total_favourites')
			->loadCount([
				'favourites as favourited' => function ($query) {
					$query->where('user_id', auth()->id());
				},
			]);

		$file->saveView();

		return Inertia::render('Files/View', [
			'file'     => new FileResource($file),
			'contents' => Inertia::lazy(function () use ($file) {
				return $file->codeFileContents();
			}),
		]);
	}

	/**
	 * Toggle whether a file is favourited or not
	 *
	 * @param File $file
	 *
	 * @return RedirectResponse
	 * @throws AuthorizationException
	 */
	public function favourite(File $file): RedirectResponse
	{
		$this->authorize('update', $file);

		$file->favourite();

		return back();
	}

	/**
	 * Delete an uploaded file
	 *
	 * @param File $file
	 *
	 * @return RedirectResponse
	 * @throws AuthorizationException
	 */
	public function delete(File $file): RedirectResponse
	{
		$this->authorize('delete', $file);

		Storage::delete($file->path);

		$file->delete();

		return back();
	}

	/**
	 * Update description/private of an uploaded file
	 *
	 * @param File $file
	 *
	 * @return RedirectResponse
	 * @throws AuthorizationException
	 */
	public function update(DigitalOceanManager $digitalOcean, File $file): RedirectResponse
	{
		$this->authorize('delete', $file);

		if (request()->has('name')) {
			$file->name = request('name');
		}
		if (request()->has('description')) {
			$file->description = request('description');
		}

		if (request()->has('private')) {
			$file->private = request('private');
		}

		$file->save();

		Storage::setVisibility($file->path, $file->private ? 'private' : 'public');
		if ($file->thumb) {
			Storage::setVisibility($file->thumb, $file->private ? 'private' : 'public');
		}
		$digitalOcean->getHttpClient()
			->delete('https://api.digitalocean.com/v2/cdn/endpoints/' . config('services.digitalocean.spaces_id') . '/cache', [
				'Content-Type' => 'application/json',
			], JsonObject::encode([
				'files' => [
					$file->path,
				],
			]));


		return back();
	}
}
