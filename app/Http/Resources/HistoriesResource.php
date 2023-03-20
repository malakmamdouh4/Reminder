<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoriesResource extends JsonResource
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
            'disease'                => $this->disease ??'',
            'degree'                 => $this->degree ??'',
            'diagnose'               => $this->diagnose ??'',
            'symptoms'               => $this->symptoms ??'',
            'tests'                  => TestHistoryResource::collection($this->tests),
        ];
    }


}
