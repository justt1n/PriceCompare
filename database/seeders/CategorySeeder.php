<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['name' => 'Phone', 'created_by' => 'Khanh'],
            ['name' => 'Laptop', 'created_by' => 'Khanh'],
            ['name' => 'Tablet', 'created_by' => 'Khanh'],
            ['name' => 'Smart Watch', 'created_by' => 'Khanh'],
        ];

        Category::insert($createMultipleData);
    }
}
