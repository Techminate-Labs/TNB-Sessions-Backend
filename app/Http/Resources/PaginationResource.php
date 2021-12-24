<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\JsonResource;
// class PaginationResource extends JsonResource

use Illuminate\Http\Resources\Json\ResourceCollection;
class PaginationResource extends ResourceCollection
{

    // public function toArray($request)
    // {
    //     return parent::toArray($request);
    // }

    // public function toResponse($request)
    // {
    //     return parent::toResponse($request);
    // }

    public function withResponse($request, $response)
    {
        $data = $response->getData(true);
        
        $first = $data['links']['first'];
        $last = $data['links']['last'];
        $prev = $data['links']['prev'];
        $next = $data['links']['next'];

        $current_page = $data['meta']['current_page'];
        $from = $data['meta']['from'];
        $last_page = $data['meta']['last_page'];
        $metaLinks = $data['meta']['links'];
        $links = array_slice($metaLinks, 1, -1);
        $path = $data['meta']['path'];
        $per_page = $data['meta']['per_page'];
        $to = $data['meta']['to'];
        $total = $data['meta']['total'];

        $data['links'] = compact('first', 'last','prev', 'next');
        $data['meta'] = compact(
            'total', 'from', 'to', 'per_page', 'current_page', 'last_page', 'path' , 'links'
        );
        
        $response->setData($data);
    }
}
