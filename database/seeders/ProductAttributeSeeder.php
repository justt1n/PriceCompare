<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['product_id' => 1, 'attribute_id' => 1, 'value' => '5.7 inch'],
            ['product_id' => 1, 'attribute_id' => 2, 'value' => '6GB'],
            ['product_id' => 1, 'attribute_id' => 3, 'value' => '128GB'],
            ['product_id' => 1, 'attribute_id' => 4, 'value' => 'Apple A15'],
            ['product_id' => 1, 'attribute_id' => 5, 'value' => '3875 mAH'],
            ['product_id' => 1, 'attribute_id' => 6, 'value' => '12MP'],
            ['product_id' => 1, 'attribute_id' => 7, 'value' => '12MP'],
        ];

        // ProductAttribute::insert($createMultipleData);
    }
}
