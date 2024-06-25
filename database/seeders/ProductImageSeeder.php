<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['url' => 'https://cdn.tgdd.vn/Products/Images/42/250258/iphone-13-256gb-1.jpg', 'product_site_id' => 1],           
            ['url' => 'https://cdn.tgdd.vn/Products/Images/42/250258/iphone-13-256gb-19.jpg', 'product_site_id' => 1],           
            ['url' => 'https://cdn.tgdd.vn/Products/Images/42/250258/iphone-13-256gb-4.jpg', 'product_site_id' => 1],           
        ];

        // ProductImage::insert($createMultipleData);
    }
}
