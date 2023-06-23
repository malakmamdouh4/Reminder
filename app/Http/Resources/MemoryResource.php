<?php

namespace App\Http\Resources;

use App\Models\Memory;
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
        $related_memories = Memory::where('id','!=',$this->id)
            ->where(['user_id'=>$this->user_id,'user_type'=>$this->user_type])
            ->get();

        return [
            'id'                     => $this->id,
            'title'                  => $this->title ??'',
            'date'                   => $this->date ?  $this->date->isoFormat('YYYY - MMMM - D') : '',
            'type'                   => $this->type ??'',
            'user_type'              => $this->user_type ?? 'family',
            'media'                  => MemoryMediaResource::collection($this->files),
            'related_memories'       => MemoriesResource::collection($related_memories),
        ];
    }


}
