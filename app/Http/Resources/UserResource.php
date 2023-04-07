<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                    => $this->id,
            'first_name'            => $this->first_name ??'',
            'last_name'             => $this->last_name ??'',
            'email'                 => $this->email ??'',
            'country_key'           => $this->country_key ??'',
            'phone'                 => $this->fullPhone ?? '',
            'date_birth'            => $this->date_birth ?? '',
            'gender'                => $this->gender ?? '',
            'type'                  => $this->type ?? '',
            'lat'                   => $this->lat ??'',
            'long'                  => $this->long ??'',
            'address'               => $this->address ??'',
            'family_id'             => (int) $this->user_id ?? 0,
            'complete_patient_info' => $this->complete_patient_info =='true'?true:false,
            'complete_giver_info'   => $this->complete_giver_info =='true'?true:false,
        ];
    }


}
