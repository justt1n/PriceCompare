<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateDdv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ddv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start_time = microtime(true);
        print("Scraping didongviet...\n");
        Log::channel('scrapper')->info("Scraping didongviet...");
        $bot = new \App\Services\Scraper\ddv();
        $bot->scrape(true, true);
        $end_time = microtime(true);
        print("Done!\n");
        Log::channel('scrapper')->info("Scraped didongviet in ".($end_time - $start_time)." sec\n");
    }
}