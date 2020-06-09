<?php

namespace App\Http\Resources;

use Faker\Factory;
use Illuminate\Http\Resources\Json\JsonResource;

class SlimCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'slug'=>$this->slug,
            'collection'=> $this->collection,
            'description'=> $this->description,
            'created_at'=> $this->created_at->diffForHumans(),
            'threads_count'=> $this->threads->count(),
            'summary_image'=> Factory::create()->imageUrl(),
            'create_at'=> $this->created_at->diffForHumans(),
            'user'=> new SlimUserResource($this->user),
        ];
    }
}
