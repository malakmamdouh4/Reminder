<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class TracksResource extends JsonResource
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
            'file'                   => $this->file ? $this->filePath : '',
            'date'                   => $this->date ?  $this->date->isoFormat('YYYY - MMMM - D') : '',
            'time'                   => $this->time ??'',
        ];
    }


}
