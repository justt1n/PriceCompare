<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateDienthoaigiakho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:dtgk';

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
        print("Scraping dienthoaigiakho...\n");
        Log::channel('scrapper')->info("Scraping dienthoaigiakho...");
        $bot = new \App\Services\Scraper\dienthoaigiakho();
        $bot->scrape(true, true);
        $end_time = microtime(true);
        print("Done!\n");
        Log::channel('scrapper')->info("Scraped dienthoaigiakho in ".($end_time - $start_time)." sec\n");
    }
}
