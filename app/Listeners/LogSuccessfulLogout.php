<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use App\Models\Setting;

class LogSuccessfulLogout
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
     * @param  \App\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        /*if(session('sekolah_id')){
            foreach (Setting::where('sekolah_id', session('sekolah_id'))->get() as $setting) {
                //$this->request->session()->put('settings_'.session('sekolah_id').'_'.$setting->key, $setting->value);
                $this->request->session()->forget('settings_'.$setting->sekolah_id.'_'.$setting->key);
            }
        } else {
            foreach (Setting::all() as $setting) {
                //$this->request->session()->put('settings_'.session('sekolah_id').'_'.$setting->key, $setting->value);
                $this->request->session()->forget('settings_'.$setting->sekolah_id.'_'.$setting->key);
            }
        }*/
        $this->request->session()->forget(['semester_id', 'semester_aktif', 'sekolah_id', 'nama_sekolah']);
    }
}
