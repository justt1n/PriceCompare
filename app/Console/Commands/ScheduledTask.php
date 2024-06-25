<?php

namespace App\Console\Commands;

use App\Models\CronjobSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use App\Mail\CronMail;

class ScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled-cron';

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
        $datas = new CronjobSchedule();
        $datas = $datas->all();
        $currentTime = date('Y-m-d H:i:s');
        Log::info("="*40);
        Log::info("Cronjob is running at " . $currentTime);
        foreach ($datas as $data) {
            switch ($data->site_id) {
                case 1:
                    $this->runCronjob($data, 'tgdd');
                    break;
                case 2:
                    $this->runCronjob($data, 'fpt');
                    break;
                case 3:
                    $this->runCronjob($data, 'tiki');
                    break;
                case 4:
                    $this->runCronjob($data, 'ddv');
                    break;
                case 5:
                    $this->runCronjob($data, 'pv');
                    break;
                case 6:
                    $this->runCronjob($data, 'dienthoaigiakho');
                    break;
                default:
                    # code...
                    break;
            }
            Log::info("=" * 40);
        }
        // Send Mail
        // $cronStatus = "Successfull 16h20";
        // Mail::to('team2_fresher_hybrid@gmail.com’')->send(new CronMail($cronStatus));
    }

    public function runCronjob($data, $site)
    {
        Log::info('-' * 40);
        if ($data['status'] == 1) {
            Artisan::call('scrape:' . $site);
            CronjobSchedule::where('site_id', $data['site_id'])->update(['status' => 2, 'new' => 2, 'update' => 2]);
            Log::info('Scraping ' . $site . '...');
            // Action Update
            // Artisan::call('update:'.$site);
        } elseif ($data['status'] == 2) {
            // Action New
            if ($data['new'] == 1) {
                Artisan::call('scrape:' . $site);
                Log::info('Initalling ' . $site . '...');
            }
            // Action Update
            if ($data['update'] == 1) {
                Artisan::call('update:' . $site);
                Log::info('Updating ' . $site . '...');
            }
        }
        Log::info('-' * 40);
    }
}
