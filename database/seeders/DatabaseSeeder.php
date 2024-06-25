<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CronjobScheduleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {

            $this->call([
                UserSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class,
                AttributeSeeder::class,
                CategoryAttributeSeeder::class,
                ProductAttributeSeeder::class,

                SiteSeeder::class,
                ProductSiteSeeder::class,
                ProductImageSeeder::class,
                CronjobScheduleSeeder::class,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        
    }
}
