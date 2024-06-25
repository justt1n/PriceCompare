<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['name' => 'Iphone 13', 'brand' => 'apple', 'public' => true, 'image' => 'https://cdn.tgdd.vn/Products/Images/42/250258/iphone-13-256gb-6.jpg', 'min_price' => 15750000, 'count_site' => 0,'min_price_site_id' => 3, 'category_id' => 1, 'created_by' => 'Khanh'],
        ];
        
        // Product::insert($createMultipleData);
    }
}
