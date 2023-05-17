<?php

namespace App\Http\Resources\V2\DEPARTMENT_STAFF_and_CADRIES;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentCadryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
