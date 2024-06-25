<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CronjobSchedule;
use App\Repositories\SiteRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CronjobController extends Controller
{
    public function index() {
        $datas = new CronjobSchedule();
        $datas = $datas->all();
        foreach ($datas as $data) {

            $tmp = new SiteRepository;
            $siteName = $tmp->getNameById($data->site_id);

            $data['site_name'] = $siteName;
        }

        return view("admin.cronjob")->with('datas',$datas);
    }

    public function run(Request $request)
    {
        // if($request->status) {
        //     foreach ($request->status as $key => $value) {
        //         $value == 1 ? $value = 2 : $value = 1;
        //         if($value == 1) {
        //             CronjobSchedule::where('site_id',$key)->update(['status' => 1]);
        //         }
        //     }
        // }

        // dd($request->all());

        $siteIdNew = [];
        if ($request->new) {
            foreach ($request->new as $siteId => $value) {


                CronjobSchedule::where('site_id',$siteId)->update(['new' => 1]);

                array_push($siteIdNew, $siteId);
            }
        }
        CronjobSchedule::whereNotIn('site_id', $siteIdNew)->update(['new' => 2]);

        $siteIdUpdate = [];
        if ($request->update) {
            foreach ($request->update as $siteId => $value) {

                CronjobSchedule::where('site_id',$siteId)->update(['update' => 1]);

                array_push($siteIdUpdate, $siteId);
            }
        }
        CronjobSchedule::whereNotIn('site_id', $siteIdUpdate)->update(['update' => 2]);

        return redirect()->route('admin.view.cronjob');
    }

    public function setTime(Request $request)
    {
        $time = $request->input('time');
        $cacheTimeInMinutes = 30 * 24 * 60; // Lưu trữ trong 30 ngày
        Cache::put('time_cron', $time, $cacheTimeInMinutes);

        return response()->json(['success'=> Cache::get('time_cron')]);
    }
}
