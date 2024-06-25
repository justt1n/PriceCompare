<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['category_id' => 1, 'attribute_id' => 1],           
            ['category_id' => 1, 'attribute_id' => 2],           
            ['category_id' => 1, 'attribute_id' => 3],           
            ['category_id' => 1, 'attribute_id' => 4],           
            ['category_id' => 1, 'attribute_id' => 5],           
            ['category_id' => 1, 'attribute_id' => 6],           
            ['category_id' => 1, 'attribute_id' => 7],    
            
            ['category_id' => 2, 'attribute_id' => 1],           
            ['category_id' => 2, 'attribute_id' => 2],           
            ['category_id' => 2, 'attribute_id' => 3],           
            ['category_id' => 2, 'attribute_id' => 4],           
            ['category_id' => 2, 'attribute_id' => 5],            
            ['category_id' => 2, 'attribute_id' => 7],           
            ['category_id' => 2, 'attribute_id' => 8],  
        ];

        // CategoryAttribute::insert($createMultipleData);
    }
}
