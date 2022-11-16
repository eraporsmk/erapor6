<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Sekolah;
use Config;

class Pengaturan extends Component
{
    public $sekolah_id;
    public $scan_masuk_start_jam;
    public $scan_masuk_start_menit;
    public $scan_masuk_end_jam;
    public $scan_masuk_end_menit;
    public $scan_pulang_start_jam;
    public $scan_pulang_start_menit;
    public $scan_pulang_end_jam;
    public $scan_pulang_end_menit;
    public $jarak;
    public $waktu_akhir_masuk_jam;
    public $waktu_akhir_masuk_menit;
    public $showForm = FALSE;
    protected $rules = [
        'sekolah_id' => 'required',
        'scan_masuk_start_jam' => 'required',
        'scan_masuk_end_jam' => 'required',
        'scan_pulang_start_jam' => 'required',
        'scan_pulang_end_jam' => 'required',
        'scan_masuk_start_menit' => 'required',
        'scan_masuk_end_menit' => 'required',
        'scan_pulang_start_menit' => 'required',
        'scan_pulang_end_menit' => 'required',
        'waktu_akhir_masuk_jam' => 'required',
        'waktu_akhir_masuk_menit' => 'required',
        'jarak' => 'required',
    ];
    protected $messages = [
        'sekolah_id.required' => 'Sekolah tidak boleh kosong!!',
        'scan_masuk_start_jam.required' => 'Jam Absen Masuk Awal tidak boleh kosong!!',
        'scan_masuk_end_jam.required' => 'Jam Absen Masuk Akhir tidak boleh kosong!',
        'scan_pulang_start_jam.required' => 'Jam Absen Pulang Awal tidak boleh kosong!!',
        'scan_pulang_end_jam.required' => 'Jam Absen Pu;ang Akhir tidak boleh kosong!',
        'scan_masuk_start_menit.required' => 'Menit Absen Masuk Awal tidak boleh kosong!!',
        'scan_masuk_end_menit.required' => 'Menit Absen Masuk Akhir tidak boleh kosong!',
        'scan_pulang_start_menit.required' => 'Menit Absen Pulang Awal tidak boleh kosong!!',
        'scan_pulang_end_menit.required' => 'Menit Absen Pulang Akhir tidak boleh kosong!',
        'waktu_akhir_masuk_jam.required' => 'Jam Waktu Akhir Masuk tidak boleh kosong!',
        'waktu_akhir_masuk_menit.required' => 'Menit Waktu Akhir Masuk tidak boleh kosong!',
        'jarak.required' => 'Jarak Maksimum tidak boleh kosong!',
        
    ];
    public function change()
    {
        $this->showForm = true;
        $this->scan_masuk_start_jam = session('settings_'.$this->sekolah_id.'_scan_masuk_end_jam');
        $this->scan_masuk_end_jam = session('settings_'.$this->sekolah_id.'_scan_masuk_end_jam');
        $this->scan_pulang_start_jam = session('settings_'.$this->sekolah_id.'_scan_pulang_start_jam');
        $this->scan_pulang_end_jam = session('settings_'.$this->sekolah_id.'_scan_pulang_end_jam');
        $this->scan_masuk_start_menit = session('settings_'.$this->sekolah_id.'_scan_masuk_start_menit');
        $this->scan_masuk_end_menit = session('settings_'.$this->sekolah_id.'_scan_masuk_end_menit');
        $this->scan_pulang_start_menit = session('settings_'.$this->sekolah_id.'_scan_pulang_start_menit');
        $this->scan_pulang_end_menit = session('settings_'.$this->sekolah_id.'_scan_pulang_end_menit');
        $this->waktu_akhir_masuk_jam = session('settings_'.$this->sekolah_id.'_waktu_akhir_masuk_jam');
        $this->waktu_akhir_masuk_menit = session('settings_'.$this->sekolah_id.'_waktu_akhir_masuk_menit');
        $this->jarak = session('settings_'.$this->sekolah_id.'_jarak');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function mount()
    {
        $this->scan_masuk_start_jam = session('settings_'.$this->sekolah_id.'_scan_masuk_start_jam');
        $this->scan_masuk_end_jam = session('settings_'.$this->sekolah_id.'_scan_masuk_end_jam');
        $this->scan_pulang_start_jam = session('settings_'.$this->sekolah_id.'_scan_pulang_start_jam');
        $this->scan_pulang_end_jam = session('settings_'.$this->sekolah_id.'_scan_pulang_end_jam');
        $this->scan_masuk_start_menit = session('settings_'.$this->sekolah_id.'_scan_masuk_start_menit');
        $this->scan_masuk_end_menit = session('settings_'.$this->sekolah_id.'_scan_masuk_end_menit');
        $this->scan_pulang_start_menit = session('settings_'.$this->sekolah_id.'_scan_pulang_start_menit');
        $this->scan_pulang_end_menit = session('settings_'.$this->sekolah_id.'_scan_pulang_end_menit');
        $this->waktu_akhir_masuk_jam = session('settings_'.$this->sekolah_id.'_waktu_akhir_masuk_jam');
        $this->waktu_akhir_masuk_menit = session('settings_'.$this->sekolah_id.'_waktu_akhir_masuk_menit');
        $this->jarak = session('settings_'.$this->sekolah_id.'_jarak');
    }
    public function save()
    {
        $this->validate();
        $data = ['scan_masuk_start_jam', 'scan_masuk_end_jam', 'scan_pulang_start_jam', 'scan_pulang_end_jam', 'scan_masuk_start_menit', 'scan_masuk_end_menit', 'scan_pulang_start_menit', 'scan_pulang_end_menit', 'waktu_akhir_masuk_jam', 'waktu_akhir_masuk_menit'];
        foreach($data as $d){
            Setting::updateOrcreate(
                [
                    'key' => $d,
                    'sekolah_id' => $this->sekolah_id,
                ],
                [
                    'value' => $this->{$d},
                ]
            );
        }
        Setting::updateOrcreate(
            [
                'key' => 'scan_masuk_start',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->scan_masuk_start_jam.':'.$this->scan_masuk_start_menit,
            ]
        );
        Setting::updateOrcreate(
            [
                'key' => 'scan_masuk_end',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->scan_masuk_end_jam.':'.$this->scan_masuk_end_menit,
            ]
        );
        Setting::updateOrcreate(
            [
                'key' => 'scan_pulang_start',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->scan_pulang_start_jam.':'.$this->scan_pulang_start_menit,
            ]
        );
        Setting::updateOrcreate(
            [
                'key' => 'scan_pulang_end',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->scan_pulang_end_jam.':'.$this->scan_pulang_end_menit,
            ]
        );
        Setting::updateOrcreate(
            [
                'key' => 'waktu_akhir_masuk',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->waktu_akhir_masuk_jam.':'.$this->waktu_akhir_masuk_menit,
            ]
        );
        Setting::updateOrcreate(
            [
                'key' => 'jarak',
                'sekolah_id' => $this->sekolah_id,
            ],
            [
                'value' => $this->jarak,
            ]
        );
        foreach (Setting::where('sekolah_id', $this->sekolah_id)->get() as $setting) {
            session(['settings_'.$this->sekolah_id.'_'.$setting->key => $setting->value]);
        }
    }
    public function render()
    {
        /*$this->scan_masuk_start = config('settings.scan_masuk_start');
        $this->scan_masuk_end = config('settings.scan_masuk_end');
        $this->scan_pulang_start = config('settings.scan_pulang_start');
        $this->scan_pulang_end = config('settings.scan_pulang_end');*/
        return view('livewire.pengaturan', [
            'data_sekolah' => Sekolah::select('sekolah_id', 'nama')->get(),
        ]);
    }
}
