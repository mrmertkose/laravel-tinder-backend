<?php

namespace App\Http\Resources;

use App\Enums\GenderTypeEnum;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this['info']->name,
            'age' => Carbon::parse($this['info']->birthday)->diff(Carbon::now())->format('%y'),
            'gender' => GenderTypeEnum::gender[$this['info']->gender],
            'searching_gender' => GenderTypeEnum::gender[$this['info']->searching_gender],
            'photos' => $this['photos'],
        ];
    }
}
