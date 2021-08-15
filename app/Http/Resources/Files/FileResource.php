<?php

namespace App\Http\Resources\Files;

use App\Http\Resources\UserResource;
use App\Models\Files\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin File */
class FileResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'type'          => $this->type,
            'mime_type'     => $this->mime_type,
            'extension'     => $this->extension,
            'path'          => $this->path,
            'size_in_bytes' => $this->size_in_bytes,
            'size'          => $this->formatSizeUnits(),
            'meta'          => $this->meta ?? [],
            'private'       => $this->private,
            'status'        => $this->status,

            'thumb' => $this->getThumbUrl(),
            'url'   => $this->getUrl(),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'is_mine' => $this->user_id === auth()->id(),
            'user_id' => $this->user_id,

            'user'             => new UserResource($this->whenLoaded('user')),
            'views'            => $this->when($this->relationLoaded('views'), $this->views),
            'favourited'       => $this->when($this->relationLoaded('favourited'), $this->favourited),
            'total_favourites' => $this->when($this->relationLoaded('total_favourites'), $this->total_favourites),

            'urls' => [
                'id'     => route('files.view', $this->id),
                'string' => route('files.view', $this->urlKey()),
            ],
        ];
    }
}
