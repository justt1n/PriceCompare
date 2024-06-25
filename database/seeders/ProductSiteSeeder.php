<?php

namespace Database\Seeders;

use App\Models\ProductSite;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['name' => 'Iphone 13', 'url' => 'https://www.thegioididong.com/dtdd/iphone-13', 'price' => 16490000, 'product_id' => 1, 'site_id' => 1],
            ['name' => 'Iphone 13', 'url' => 'https://fptshop.com.vn/dien-thoai/iphone-13', 'price' => 15990000, 'product_id' => 1, 'site_id' => 2],
            ['name' => 'Iphone 13', 'url' => 'https://tiki.vn/apple-iphone-13-hang-chinh-hang-p184059211.html', 'price' => 15750000, 'product_id' => 1, 'site_id' => 3],
        ];

        // ProductSite::insert($createMultipleData);
    }
}
