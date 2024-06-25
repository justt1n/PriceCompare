<?php

namespace Database\Seeders;

use App\Models\CronjobSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CronjobScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createMultipleData = [
            [
                'site_id' => 1,
                'status' => 1,
                'new' => 1,
                'update' => 1,
            ],
            [
                'site_id' => 2,
                'status' => 1,
                'new' => 2,
                'update' => 2,
            ],
            [
                'site_id' => 3,
                'status' => 1,
                'new' => 2,
                'update' => 2,
            ],
            [
                'site_id' => 4,
                'status' => 1,
                'new' => 2,
                'update' => 2,
            ],
            [
                'site_id' => 5,
                'status' => 1,
                'new' => 2,
                'update' => 2,
            ],
            [
                'site_id' => 6,
                'status' => 1,
                'new' => 2,
                'update' => 2,
            ],
        ];

        CronjobSchedule::insert($createMultipleData);
    }
}
