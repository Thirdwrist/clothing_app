<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
          'thread'=> $this->thread,
          'description'=> $this->description,
            'user'=>[
              'id'=> $this->user->id,
              'name'=>$this->user->name,
              'username'=> $this->user->username
            ],
          'posts'=> new PostCollection($this->posts)
        ];
    }
}
