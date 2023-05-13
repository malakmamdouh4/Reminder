<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class ExamsResource extends JsonResource
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
            'result'                 => (int)$this->result ?? 0,
            'date'                   => $this->date ??'',
            'time'                   => $this->getTime($this->time) ??'',
        ];
    }


}
