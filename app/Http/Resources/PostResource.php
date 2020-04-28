<?php

namespace App\Http\Resources;

use function config;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class PostResource extends JsonResource
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
            'id'=> $this->id,
            'image_url'=> $this->image(),
            'position'=> $this->position,
            'description'=> $this->description,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at
        ];
    }

    private function image()
    {
        if($this->connection === 'local'){
            return Storage::disk('local')->url($this->image_url);
        }
        elseif ($this->connection ==='s3')
        {
            return Storage::disk('s3')->url($this->image_url);
        }
    }
}
