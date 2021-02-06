<?php

namespace App\Http\Resources;

use App\Http\Resources\Files\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'   => $this->id,
			'name' => $this->name,

			'favouriteFiles' => FileResource::collection($this->whenLoaded('favouriteFiles')),
			'files'          => FileResource::collection($this->whenLoaded('files')),
		];
	}
}
