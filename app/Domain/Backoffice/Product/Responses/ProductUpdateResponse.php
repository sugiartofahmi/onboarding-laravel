<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Responses;

use App\Models\Product;

class ProductUpdateResponse
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
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'thumbnail_url' => $this->product->thumbnail_path
                ? "{$awsUrl}/{$awsBucket}/{$this->product->thumbnail_path}"
                : null,
            'image_urls' => $this->product->images->map(function ($image) use ($awsUrl, $awsBucket) {
                return "{$awsUrl}/{$awsBucket}/{$image->image_path}";
            })->values()->toArray(),
            'stock' => $this->product->stock,
            'status' => $this->product->status,
            'created_at' => $this->product->created_at,
            'updated_at' => $this->product->updated_at,
        ];
    }
}
