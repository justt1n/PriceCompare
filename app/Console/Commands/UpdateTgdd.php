<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTgdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tgdd';

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
        print("Scraping tgdd...\n");
        Log::channel('scrapper')->info("Scraping tgdd...");
        $bot = new \App\Services\Scraper\tgdd();
        $bot->scrape(true, true);
        $end_time = microtime(true);
        print("Done!\n");
        Log::channel('scrapper')->info("Scraped tgdd in ".($end_time - $start_time)." sec\n");
    }
}