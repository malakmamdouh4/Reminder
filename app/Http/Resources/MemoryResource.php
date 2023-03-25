<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MemoryResource extends JsonResource
{
    use ApiTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'title'                  => $this->title ??'',
            'date'                   => $this->date ??'',
            'type'                   => $this->type ??'',
            'media'                  => MemoryMediaResource::collection($this->files),
        ];
    }


}
