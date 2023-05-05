<?php

namespace App\Http\Resources;

use App\Http\Resources\Files\FileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,

            'profile_photo_url' => $this->profile_photo_url,
            'private_uploads' => $this->private_uploads,

            'favouriteFiles' => FileResource::collection($this->whenLoaded('favouriteFiles')),
            'files'          => FileResource::collection($this->whenLoaded('files')),
        ];
    }
}
