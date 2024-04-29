<?php

namespace App\Http\Resources\User\Menu;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $menu = $this->collection->map(function ($item) {
            return $item->map(function ($menu) {
                return [
                    'label' => $menu->label,
                    'icon' => $menu->icon,
                    'to' => $menu->url
                ];
            });
        })->values()->toArray();

        return $menu;
    }
}
