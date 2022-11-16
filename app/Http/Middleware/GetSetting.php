<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\Status_penilaian;
use Config;

class GetSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //dd(session('sekolah_id'));
        if (Schema::hasTable('settings')) {
            foreach (Setting::all() as $setting) {
                if($setting->sekolah_id && $setting->semester_id){
                    Config::set('global.'.session('sekolah_id').'.'.session('semester_aktif').'.'.$setting->key, $setting->value);
                } elseif($setting->sekolah_id && !$setting->semester_id){
                    Config::set('global.'.session('sekolah_id').'.'.$setting->key, $setting->value);
                } else {
                    Config::set('global.'.$setting->key, $setting->value);
                }
                //theme
            }
            $status_penilaian = Status_penilaian::where('sekolah_id', session('sekolah_id'))->where('semester_id', session('semester_aktif'))->first();
            if($status_penilaian){
                Config::set('global.'.session('sekolah_id').'.'.session('semester_aktif').'.status_penilaian', $status_penilaian->status);
            } else {
                Config::set('global.'.session('sekolah_id').'.'.session('semester_aktif').'.status_penilaian', 0);
            }
            Config::set('app.timezone', config('global.'.session('sekolah_id').'.zona'));
            Config::set('custom.custom.theme', session('theme'));
        }
        $cara_penilaian = config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian');
        if(!$cara_penilaian){
            Config::set('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian', 'sederhana');
        }
        return $next($request);
    }
}
