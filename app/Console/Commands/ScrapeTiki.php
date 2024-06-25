<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScrapeTiki extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:tiki';

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
        print("Scraping tiki...\n");
        Log::channel('scrapper')->info("Scraping tiki...");
        $bot = new \App\Services\Scraper\tiki();
        $bot->scrape(true, false);
        $end_time = microtime(true);
        print("Done!\n");
        Log::channel('scrapper')->info("Scraped tiki in ".($end_time - $start_time)." sec\n");
    }
}
