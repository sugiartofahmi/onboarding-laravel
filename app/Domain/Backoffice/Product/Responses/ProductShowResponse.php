<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Responses;

use App\Models\Product;

class ProductShowResponse
{
    public function __construct(
        private Product $product
    ) {}

    public function toArray(): array
    {
        $awsUrl = config('filesystems.disks.s3.url');
        $awsBucket = config('filesystems.disks.s3.bucket');

        return [
            'id' => $this->product->id,
            'category_id' => $this->product->category_id,
            'category' => [
                'id' => $this->product->category->id,
                'name' => $this->product->category->name,
                'slug' => $this->product->category->slug,
            ],
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'thumbnail_url' => $this->product->thumbnail_path
                ? "{$awsUrl}/{$awsBucket}/{$this->product->thumbnail_path}"
                : null,
            'stock' => $this->product->stock,
            'status' => $this->product->status,
            'image_urls' => $this->product->images->map(function ($image) use ($awsUrl, $awsBucket) {
                return "{$awsUrl}/{$awsBucket}/{$image->image_path}";
            })->values()->toArray(),
            'created_at' => $this->product->created_at,
            'updated_at' => $this->product->updated_at,
        ];
    }
}
