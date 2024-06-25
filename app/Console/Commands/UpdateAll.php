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

class UpdateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:all';

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
        print("Updating...\n");
        $tgdd = new tgdd();
        $tiki = new tiki();
        $fpt = new fpt();
        $ddv = new ddv();
        $phongvu = new phongvu();
        $dtgk = new dienthoaigiakho();
        print("Updating tgdd...\n");
        info("Updating tgdd...\n");
        Scraper::scrape($tgdd,false, true);
        print("Updating ddv...\n");
        info("Updating ddv...\n");
        Scraper::scrape($ddv,false, true);
        print("Updating dtgk...\n");
        info("Updating dtgk...\n");
        Scraper::scrape($dtgk,false, true);
        print("Updating tiki...\n");
        info("Updating tiki...\n");
        Scraper::scrape($tiki, false, true);
        print("Updating phongvu...\n");
        info("Updating phongvu...\n");
        Scraper::scrape($phongvu, false, true);
        print("Updating fpt...\n");
        info("Updating fpt...\n");
        Scraper::scrape($fpt, false, true);
        
        $end = microtime(true);
        $executionTime = $end - $start;
        print("Script execution time: " . $executionTime . " seconds\n");
        info("Script execution time: " . $executionTime . " seconds\n");
    }

}