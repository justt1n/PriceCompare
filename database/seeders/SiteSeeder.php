<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['url' => 'https://www.thegioididong.com', 'status' => 1, 'created_by' => 'Khanh'],
            ['url' => 'https://fptshop.com.vn', 'status' => 1, 'created_by' => 'Khanh'],
            ['url' => 'https://tiki.vn', 'status' => 1, 'created_by' => 'Khanh'],
            ['url' => 'https://didongviet.vn', 'status' => 1, 'created_by' => 'Khanh'],
            ['url' => 'https://phongvu.vn', 'status' => 1, 'created_by' => 'Khanh'],           
            ['url' => 'https://dienthoaigiakho.vn/', 'status' => 1, 'created_by' => 'Khanh'],           
        ];

        Site::insert($createMultipleData);
    }
}
