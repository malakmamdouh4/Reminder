<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'date'                   => $this->date ?  $this->date->isoFormat('YYYY - MMMM - D') : '',
            'from'                   => $this->from ??'',
            'to'                     => $this->to ??'',
            'repeat'                 => $this->repeat ??'',
            'is_important'           => $this->is_important == 'true' ? true : false,
            'description'            => $this->description ??'',
            'is_completed'           => $this->is_completed == 'true' ? true : false,
        ];
    }


}
