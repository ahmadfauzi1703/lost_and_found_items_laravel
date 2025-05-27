<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
            // 'meta' => [
            //     'total_items' => $this->total(),
            //     'per_page' => $this->perPage(),
            //     'current_page' => $this->currentPage(),
            //     'last_page' => $this->lastPage(),
            // ],
        ];
    }

    public function paginationInformation($request, $paginated, $default)
    {
        // Hapus meta information dari response
        return [];
    }
}
