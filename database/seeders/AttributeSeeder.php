<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            ['name' => 'Size'],
            ['name' => 'Ram'],
            ['name' => 'Rom'],
            ['name' => 'Chip'],
            ['name' => 'Battery'],
            ['name' => 'Main camera'],
            ['name' => 'Front camera'],
            ['name' => 'VGA'],
        ];

        // Attribute::insert($createMultipleData);
    }
}
