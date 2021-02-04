<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\Files\File;
use Inertia\Inertia;

class FileController extends Controller
{
	public function list()
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

		$files = $user->files()
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
			'files' => FileResource::collection($files),
		]);
	}

	public function view(File $file)
	{
		return Inertia::render('Files/View', [
			'file' => new FileResource($file),
		]);
	}
}
