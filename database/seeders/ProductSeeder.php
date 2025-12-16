<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            // Electronics
            ['name' => 'iPhone 15 Pro Max', 'category' => 'Electronics', 'price' => 21999000, 'stock' => 25],
            ['name' => 'Samsung Galaxy S24 Ultra', 'category' => 'Electronics', 'price' => 19999000, 'stock' => 30],
            ['name' => 'MacBook Pro M3 14"', 'category' => 'Electronics', 'price' => 32999000, 'stock' => 15],
            ['name' => 'Sony WH-1000XM5', 'category' => 'Electronics', 'price' => 5499000, 'stock' => 50],
            ['name' => 'iPad Air M2', 'category' => 'Electronics', 'price' => 12999000, 'stock' => 5], // low stock

            // Fashion
            ['name' => 'Nike Air Max 90', 'category' => 'Fashion', 'price' => 1899000, 'stock' => 100],
            ['name' => 'Levi\'s 501 Original Jeans', 'category' => 'Fashion', 'price' => 1299000, 'stock' => 80],
            ['name' => 'Uniqlo Ultra Light Down Jacket', 'category' => 'Fashion', 'price' => 999000, 'stock' => 3], // low stock
            ['name' => 'Adidas Ultraboost 23', 'category' => 'Fashion', 'price' => 2799000, 'stock' => 45],

            // Food & Beverage
            ['name' => 'Arabica Coffee Beans 1kg', 'category' => 'Food & Beverage', 'price' => 250000, 'stock' => 200],
            ['name' => 'Organic Green Tea 100pcs', 'category' => 'Food & Beverage', 'price' => 150000, 'stock' => 150],
            ['name' => 'Premium Olive Oil 500ml', 'category' => 'Food & Beverage', 'price' => 189000, 'stock' => 0], // out of stock

            // Home & Garden
            ['name' => 'Philips Air Purifier', 'category' => 'Home & Garden', 'price' => 3499000, 'stock' => 20],
            ['name' => 'Dyson V15 Vacuum Cleaner', 'category' => 'Home & Garden', 'price' => 12999000, 'stock' => 8], // low stock
            ['name' => 'IKEA KALLAX Shelf Unit', 'category' => 'Home & Garden', 'price' => 1499000, 'stock' => 35],

            // Sports & Outdoors
            ['name' => 'Yoga Mat Premium 6mm', 'category' => 'Sports & Outdoors', 'price' => 350000, 'stock' => 120],
            ['name' => 'Garmin Forerunner 265', 'category' => 'Sports & Outdoors', 'price' => 6999000, 'stock' => 18],
            ['name' => 'Camping Tent 4 Person', 'category' => 'Sports & Outdoors', 'price' => 2500000, 'stock' => 2], // low stock

            // Books & Media
            ['name' => 'Atomic Habits - James Clear', 'category' => 'Books & Media', 'price' => 149000, 'stock' => 500],
            ['name' => 'The Psychology of Money', 'category' => 'Books & Media', 'price' => 129000, 'stock' => 300],
            ['name' => 'Spotify Premium 12 Months', 'category' => 'Books & Media', 'price' => 699000, 'stock' => 999],

            // Health & Beauty
            ['name' => 'SK-II Facial Treatment Essence', 'category' => 'Health & Beauty', 'price' => 2899000, 'stock' => 40],
            ['name' => 'Vitamin D3 1000IU 90 Caps', 'category' => 'Health & Beauty', 'price' => 250000, 'stock' => 0], // out of stock
            ['name' => 'Cetaphil Gentle Cleanser 500ml', 'category' => 'Health & Beauty', 'price' => 289000, 'stock' => 75],

            // Toys & Games
            ['name' => 'LEGO Star Wars Millennium Falcon', 'category' => 'Toys & Games', 'price' => 2499000, 'stock' => 10],
            ['name' => 'PlayStation 5 Console', 'category' => 'Toys & Games', 'price' => 8999000, 'stock' => 7], // low stock
            ['name' => 'Nintendo Switch OLED', 'category' => 'Toys & Games', 'price' => 5499000, 'stock' => 12],

            // Automotive
            ['name' => 'Michelin Pilot Sport 4 225/45R17', 'category' => 'Automotive', 'price' => 2100000, 'stock' => 60],
            ['name' => 'Mobil 1 Synthetic Oil 5W-30 4L', 'category' => 'Automotive', 'price' => 650000, 'stock' => 100],
            ['name' => 'Car Dash Cam 4K', 'category' => 'Automotive', 'price' => 1299000, 'stock' => 4], // low stock

            // Office Supplies
            ['name' => 'Logitech MX Master 3S', 'category' => 'Office Supplies', 'price' => 1599000, 'stock' => 55],
            ['name' => 'Herman Miller Aeron Chair', 'category' => 'Office Supplies', 'price' => 24999000, 'stock' => 5], // low stock
            ['name' => 'Dell UltraSharp 27" 4K Monitor', 'category' => 'Office Supplies', 'price' => 8999000, 'stock' => 15],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('name', $productData['category']);

            if (!$category) {
                continue;
            }

            $stock = $productData['stock'];
            $status = match (true) {
                $stock === 0 => 'out_of_stock',
                $stock <= 10 => 'low_stock',
                default => 'active',
            };

            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => "High quality {$productData['name']} from {$productData['category']} category.",
                'price' => $productData['price'],
                'stock' => $stock,
                'status' => $status,
                'thumbnail_path' => null,
            ]);
        }

        $this->command->info('Created ' . count($products) . ' products.');
    }
}
