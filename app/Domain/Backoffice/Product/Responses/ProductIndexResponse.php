<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $products
    ) {}

    public function toArray(): array
    {
        $awsUrl = config('filesystems.disks.s3.url');
        $awsBucket = config('filesystems.disks.s3.bucket');

        return $this->products->map(function ($product) use ($awsUrl, $awsBucket) {
            return [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ],
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'thumbnail_url' => $product->thumbnail_path
                    ? "{$awsUrl}/{$awsBucket}/{$product->thumbnail_path}"
                    : null,
                'stock' => $product->stock,
                'status' => $product->status,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        })->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->products;
    }
}
