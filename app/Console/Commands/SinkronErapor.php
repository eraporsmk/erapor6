<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Mst_wilayah;
use App\Models\Kompetensi_dasar;
use App\Models\User;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Capaian_pembelajaran;
use Carbon\Carbon;
use Storage;

class SinkronErapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:erapor {satuan?} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $list_data = [
            'semua_data',
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran', 
        ];
        $satuan = $this->argument('satuan');
        if($satuan){
            $semester = Semester::where('periode_aktif', 1)->first();
            $user = User::where('email', $this->argument('email'))->first();
            $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                $query->whereRoleIs('admin', $semester->nama);
            }])->find($user->sekolah_id);
        } else {
            $email = $this->ask('Email Administrator:');
            $user = User::where('email', $email)->first();
            if($user){
                $semester = Semester::where('periode_aktif', 1)->first();
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                        $query->whereRoleIs('admin', $semester->nama);
                    }])->find($user->sekolah_id);
                    $satuan = $this->choice(
                        'Pilih data untuk di sinkronisasi!',
                        $list_data
                    );
                } else {
                    $this->error('Email '.$email.' tidak memiliki akses Administrator');
                    exit;
                }
            } else {
                $this->error('Email '.$email.' tidak terdaftar');
                exit;
            }            
        }
        $created_at = NULL;
        if($satuan == 'wilayah'){
            $created_at = Mst_wilayah::orderBy('created_at', 'DESC')->first();
        } elseif($satuan == 'kompetensi-dasar'){
            $created_at = Kompetensi_dasar::orderBy('created_at', 'DESC')->first();
        } elseif($satuan == 'capaian-pembelajaran'){
            $created_at = Capaian_pembelajaran::orderBy('created_at', 'DESC')->first();
        }
        if(!$created_at){
            $created_at = (object) ['created_at' => '2021-10-23 01:47:54'];
        }
        $args = [
            'created_at' => Carbon::parse($created_at->created_at)->format('Y-m-d H:i:s'),
        ];
        $server_dashboard = [
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran',
        ];
        if(in_array($satuan, $server_dashboard)){
            $this->info('Mengambil data '.$this->get_table($satuan));
            $hitung_data = $this->ambil_data($satuan.'/hitung', $args);
            $this->hitung_data($hitung_data, $satuan, $sekolah, $semester);
        } else {
            $sync_data = [
                'wilayah', 
                'kompetensi-dasar', 
                'capaian-pembelajaran',
            ];
            foreach($sync_data as $satuan){
                $this->info('Mengambil data '.$this->get_table($satuan));
                $hitung_data = $this->ambil_data($satuan.'/hitung', $args);
                $this->hitung_data($hitung_data, $satuan, $sekolah, $semester);
            }
        }
    }
    private function hitung_data($hitung_data, $satuan, $sekolah, $semester){
        if($hitung_data && $hitung_data->dapodik){
            $limit = 250;
            $this->info('Memproses data '.$this->get_table($satuan));
            $bar = $this->output->createProgressBar($hitung_data->dapodik);
            $bar->start(0);
            if($hitung_data->dapodik > $limit){
                for ($counter = 0; $counter <= $hitung_data->dapodik; $counter += $limit) {
                    $args = [
                        'created_at' => '2021-10-23 01:47:54',
                        'offset' => $counter,
                        'limit' => $limit,
                    ];
                    $referensi = $this->ambil_data($satuan, $args);
                    $this->proses_data($referensi, $satuan, $sekolah->user, $semester, $bar);
                }
            } else {
                $args = [
                    'created_at' => '2021-10-23 01:47:54',
                    'offset' => 0,
                    'limit' => $limit,
                ];
                $referensi = $this->ambil_data($satuan, $args);
                $this->proses_data($referensi, $satuan, $sekolah->user, $semester, $bar);
            }
            $this->info("\n".'Sinkronisasi '.$this->get_table($satuan).' berhasil'."\n"."\n");
        } else {
            return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Data Anda telah lengkap!');
        }
    }
    private function proses_data($referensi, $satuan, $user, $semester, $bar){
        $function = 'simpan_'.str_replace('-', '_', $satuan);
        $this->{$function}($referensi->dapodik, $satuan, $user, $semester, $bar);
    }
    private function simpan_wilayah($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Wilayah';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_wilayah($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function simpan_kompetensi_dasar($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Kompetensi Dasar';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_kd($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function simpan_capaian_pembelajaran($dapodik, $satuan, $user, $semester, $bar){
        $i=1;
        $record['table'] = 'Referensi Capaian Pembelajaran';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_cp($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync_'.$user->sekolah_id.'.json', json_encode($record));
            $i++;
            $bar->advance();
        }
        $bar->finish();
    }
    private function get_table($table){
        $list_data = [
            'semua_data' => 'Semua Referensi e-Rapor SMK', 
            'wilayah' => 'Referensi Wilayah', 
            'kompetensi-dasar' => 'Referensi Kompetensi Dasar', 
            'capaian-pembelajaran' => 'Referensi Capaian Pembelajaran'
        ];
        if(isset($list_data[$table])){
            return $list_data[$table];
        }
        return $table;
    }
    private function ambil_data($satuan, $data_sync){
        try {
            $response = Http::post($this->url_server('dashboard', 'api/sinkronisasi/'.$satuan), $data_sync);
            if($response->status() == 200){
                return $response->object();
            } else {
                return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$response->status());
                return false;
            }
            return false;
        } catch (\Exception $e){
            return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: 500');
        }
    }
    private function url_server($server, $ep){
        return config('erapor.'.$server).$ep;
    }
    
    private function proses_kd($data){
        Kompetensi_dasar::withTrashed()->updateOrCreate(
            [
                'kompetensi_dasar_id' => $data->kompetensi_dasar_id,
            ],
            [
                'id_kompetensi' => $data->id_kompetensi,
                'kompetensi_id' => $data->kompetensi_id,
                'mata_pelajaran_id' => $data->mata_pelajaran_id,
                'kelas_10' => $data->kelas_10,
                'kelas_11' => $data->kelas_11,
                'kelas_12' => $data->kelas_12,
                'kelas_13' => $data->kelas_13,
                'id_kompetensi_nas' => $data->id_kompetensi_nas,
                'kompetensi_dasar' => $data->kompetensi_dasar,
                'kompetensi_dasar_alias' => $data->kompetensi_dasar_alias,
                'user_id' => $data->user_id,
                'kurikulum' => $data->kurikulum,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'deleted_at' => $data->deleted_at,
                'last_sync' => $data->last_sync,
            ]
        );
    }
    private function proses_wilayah($wilayah){
        $data = Mst_wilayah::updateOrCreate(
            [
                'kode_wilayah' => $wilayah->kode_wilayah,
            ],
            [
                'nama' => $wilayah->nama,
                'id_level_wilayah' => $wilayah->id_level_wilayah,
                'mst_kode_wilayah' => $wilayah->mst_kode_wilayah,
                'negara_id' => $wilayah->negara_id,
                'asal_wilayah' => $wilayah->asal_wilayah,
                'kode_bps' => $wilayah->kode_bps,
                'kode_dagri' => $wilayah->kode_dagri,
                'kode_keu' => $wilayah->kode_keu,
                'created_at' => $wilayah->created_at,
                'updated_at' => $wilayah->updated_at,
                'deleted_at' => $wilayah->deleted_at,
                'last_sync' => $wilayah->last_sync,
            ]
        );
        return $data;
    }
    private function proses_cp($data){
        Capaian_pembelajaran::updateOrCreate(
            [
                'cp_id' => $data->cp_id,
            ],
            [
                'mata_pelajaran_id' => $data->mata_pelajaran_id,
                'fase' => $data->fase,
                'elemen' => $data->elemen,
                'deskripsi' => $data->deskripsi,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
                'last_sync' => $data->last_sync,
            ]
        );
    }
}
