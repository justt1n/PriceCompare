<?php

namespace App\Console\Commands;

use App\Services\Scraper\ddv;
use App\Services\Scraper\dienthoaigiakho;
use App\Services\Scraper\phongvu;
use App\Services\Scraper\Scraper;
use Illuminate\Console\Command;
use App\Services\Scraper\tiki;
use App\Services\Scraper\tgdd;
use App\Services\Scraper\fpt;

class ScrapeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:all';

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
        $start = microtime(true);
        print("Scraping...\n");
        $tgdd = new tgdd();
        $tiki = new tiki();
        $fpt = new fpt();
        $ddv = new ddv();
        $phongvu = new phongvu();
        $dtgk = new dienthoaigiakho();
        print("Scraping tgdd...\n");
        info("Scraping tgdd...\n");
        Scraper::scrape($tgdd,false, false);
        print("Scraping ddv...\n");
        info("Scraping ddv...\n");
        Scraper::scrape($ddv,false, false);
        print("Scraping dtgk...\n");
        info("Scraping dtgk...\n");
        Scraper::scrape($dtgk,false, false);
        print("Scraping tiki...\n");
        info("Scraping tiki...\n");
        Scraper::scrape($tiki, false, false);
        print("Scraping phongvu...\n");
        info("Scraping phongvu...\n");
        Scraper::scrape($phongvu, false, false);
        print("Scraping fpt...\n");
        info("Scraping fpt...\n");
        Scraper::scrape($fpt, false, false);
        
        $end = microtime(true);
        $executionTime = $end - $start;
        print("Script execution time: " . $executionTime . " seconds\n");
        info("Script execution time: " . $executionTime . " seconds\n");
    }

}