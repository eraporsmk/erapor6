<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Setting;
use Config;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if($this->request->semester){
            $semester = Semester::where('semester_id', $this->request->semester)->first();
        } else {
            $semester = Semester::where('periode_aktif', 1)->first();
        }
        $this->request->session()->put('semester_id', $semester->nama);
        $this->request->session()->put('semester_aktif', $this->request->semester);
        $sekolah_id = ($event->user->sekolah_id) ? $event->user->sekolah_id : NULL;
        $nama_sekolah = ($event->user->sekolah_id) ? $event->user->sekolah->nama : config('app.name');
        $this->request->session()->put('sekolah_id', $sekolah_id);
        $this->request->session()->put('nama_sekolah', $nama_sekolah);
        $this->request->session()->put('user_id', $event->user->user_id);
        $this->request->session()->put('guru_id', $event->user->guru_id);
        $this->request->session()->put('peserta_didik_id', $event->user->peserta_didik_id);
        $user = $event->user;
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->last_login_ip = $this->request->ip();
        $user->save();
        //dd($sekolah_id);
        /*if($sekolah_id){
            foreach (Setting::where('sekolah_id', $sekolah_id)->get() as $setting) {
                //Config::set('settings.'.$sekolah_id.'.'.$setting->key, $setting->value);
                $this->request->session()->put('settings_'.$sekolah_id.'_'.$setting->key, $setting->value);
            }
        } else {
            foreach (Setting::all() as $setting) {
                //$this->request->session()->put('settings_'.session('sekolah_id').'_'.$setting->key, $setting->value);
                $this->request->session()->put('settings_'.$setting->sekolah_id.'_'.$setting->key, $setting->value);
            }
        }*/
    }
}
