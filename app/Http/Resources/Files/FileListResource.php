<?php

namespace App\Http\Resources\Files;

use Illuminate\Http\Resources\Json\JsonResource;

class FileListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data'          => $this->resource['files']->data,
            'links'         => $this->resource['files']->links,
            'meta'          => $this->resource['files']->meta,
            'is_favourites' => $this->resource['is_favourites'],
            'order_by'      => $this->resource['order_by'],
            'order'         => $this->resource['order'],
            'filter'        => $this->resource['filter'],
        ];
    }
}
