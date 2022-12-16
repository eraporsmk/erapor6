<?php

namespace App\Http\Livewire\Sinkronisasi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Sekolah;
use App\Models\Sync_log;
use Storage;
use Artisan;

class Erapor extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $status = '';
    public $prosesSync = FALSE;
    public $respon_artisan;
    public $table_sync = [];
    
    protected $listeners = [
        'delaySync',
        'prosesSync',
        'finishSync',
        'confirmed',
        'refresh' => '$refresh'
    ];
    public function render()
    {
        return view('livewire.sinkronisasi.erapor', [
            'sekolah' => Sekolah::find(session('sekolah_id')),
            'last_sync' => Sync_log::where('user_id', session('user_id'))->first(),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Sinkronisasi'], ['name' => 'Kirim Data e-Rapor']
            ]
        ]);
    }
    public function mount(){
        $table_sync = table_sync();
        foreach($table_sync as $table){
            $this->table_sync[] = [
                'data' => nama_table($table),
                'count' => get_table($table, session('sekolah_id'), substr(session('semester_aktif'), 0, 4), session('semester_aktif'), 1),
            ];
        }
    }
    public function mulaiKirim(){
        $this->status = 'Menyiapkan pengiriman data';
        $this->show = !$this->show;
        $this->emit('delaySync');
    }
    public function delaySync(){
        if(!$this->prosesSync){
            $this->emit('prosesSync');
        }
    }
    public function prosesSync(){
        $this->prosesSync = TRUE;
        $table_sync = table_sync();
        foreach($table_sync as $sync){
            Artisan::call('kirim:erapor', [
                'table' => $sync,
                'sekolah_id' => session('sekolah_id'),
                'tahun_ajaran_id' => substr(session('semester_aktif'), 0, 4),
                'semester_id' => session('semester_aktif'),
                'akses' => 1,
                'user_id' => session('user_id'),
            ]);
        }
        $this->status = 'Menyelesaikan pengiriman data';
        $this->respon_artisan = Artisan::output();
        $this->emit('finishSync');
    }
    public function finishSync(){
        $this->reset(['prosesSync', 'show', 'status', 'table_sync']);
        if($this->respon_artisan){
            $response = Str::of($this->respon_artisan)->between('{', '}');
            $response = json_decode('{'.$response.'}');
            $this->alert($response->status, $response->title, [
                'text' => $response->message,
                'allowOutsideClick' => false,
                'toast' => false,
                'timer' => null,
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
            ]);
        } else {
            $this->alert('success', 'Sukses', [
                'text' => 'Pengiriman data e-Rapor berhasil',
                'allowOutsideClick' => false,
                'toast' => false,
                'timer' => null,
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
            ]);
        }
    }
    public function confirmed(){
        $this->hapus_file();
        $this->emit('refresh');
    }
    private function hapus_file(){
        Storage::disk('public')->delete('proses_sync_'.session('sekolah_id').'.json');
    }
}
