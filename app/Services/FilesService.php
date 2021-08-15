<?php

namespace App\Services;

use App\Http\Resources\Files\FileResource;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class FilesService
{

    public function __construct(public User $user) { }

    #[Pure]
    public static function forUser(User $user): static
    {
        return new static($user);
    }

    public function inertiaFavourites(): Response
    {
        return Inertia::render('Files/List', $this->favourites());
    }

    #[ArrayShape(['files' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection", 'is_favourites' => "\false|mixed"])]
    public function favourites(): array
    {
        return $this->filesList(true);
    }


    #[ArrayShape(['files' => "mixed", 'is_favourites' => "bool", 'order_by' => "mixed", 'order' => "mixed", 'filter' => "mixed"])]
    private function filesList(bool $favourites): array
    {
        if (request()->has('order_by')) {
            if (!in_array(request('order_by'), ['id', 'created', 'size', 'type', 'views', 'favourites'])) {
                throw ValidationException::withMessages([
                    'order_by' => 'Invalid order by type',
                ]);
            }
        }
        if (request()->has('order')) {
            if (!in_array(request('order'), ['desc', 'asc'])) {
                throw ValidationException::withMessages([
                    'order_by' => 'Invalid order type',
                ]);
            }
        }
        if (request()->has('filter')) {
            if (!in_array(request('filter'), ['all', 'image', 'video', 'audio', 'code'])) {
                throw ValidationException::withMessages([
                    'order_by' => 'Invalid filter type',
                ]);
            }
        }

        $files = $this->user->files();

        if ($favourites) {
            $files = $this->user->favouriteFiles();
        }

        $files = $files
            ->withCount('views as views')
            ->withCount('favourites as total_favourites')
            ->withCount([
                'favourites as favourited' => function ($query) {
                    $query->where('user_id', $this->user->id);
                },
            ])
            ->when(request('filter', 'all') !== 'all', function ($query) {
                return $query->where('type', request('filter'));
            })
            ->when(request()->has('order_by'), function ($query) {
                $orderBy = request('order_by', 'id');
                $order   = request('order', 'desc');

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
                if ($orderBy === 'id') {
                    return $query->orderBy('id', $order);
                }
            }, function ($query) {
                return $query->orderBy('id', request('order', 'desc'));
            })
            ->paginate(10);

        return [
            'files'         => FileResource::collection($files)->toResponse(request())->getData(),
            'is_favourites' => $favourites,
            'order_by'      => request('order_by', 'id'),
            'order'         => request('order', 'desc'),
            'filter'        => request('filter', 'any'),
        ];
    }

    public function inertiaFiles(): Response
    {
        return Inertia::render('Files/List', $this->files());
    }

    #[ArrayShape(['files' => "\Illuminate\Http\Resources\Json\AnonymousResourceCollection", 'is_favourites' => "\false|mixed"])]
    public function files(): array
    {
        return $this->filesList(false);
    }

}
