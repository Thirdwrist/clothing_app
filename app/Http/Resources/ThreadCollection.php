<?php

namespace App\Http\Resources;

use App\Models\Thread;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ThreadCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'threads'=>ThreadResource::collection($this->collection),
        ];
    }
}
