<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [

            'title' => $this->title,
            'author' => $this->author,
            'genre' => $this->genre,
            'price' => '$' . $this->price,
            'stock_quantity' => $this->stock_quantity

        ];
    }
}
