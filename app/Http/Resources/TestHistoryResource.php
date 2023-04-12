<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class TestHistoryResource extends JsonResource
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
            'test'                   => $this->test ??'',
            'result'                 => $this->result ??'',
            'date'                   => $this->date ?  $this->date->isoFormat('YYYY - MMMM - D') : '',
            'history_id'             => $this->history_id ??'',
        ];
    }


}
