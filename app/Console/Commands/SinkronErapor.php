<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Mst_wilayah;
use App\Models\Kompetensi_dasar;
use Carbon\Carbon;
use Storage;

class SinkronErapor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:erapor {satuan?}';

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
        if($this->argument('satuan') == 'wilayah'){
            $updated_at = Mst_wilayah::orderBy('updated_at', 'DESC')->first();
            $args = [
                'updated_at' => Carbon::parse($updated_at->updated_at)->format('Y-m-d H:i:s'),
            ];
        } else {
            $args = [];
        }
        $ambil_data = $this->ambil_data($this->argument('satuan'), $args);
        if($ambil_data){
            $satuan = str_replace('-', '_', $this->argument('satuan'));
            $function = 'simpan_'.$satuan;
            $hasil_data = (isset($ambil_data->dapodik)) ? $ambil_data->dapodik : $ambil_data->count;
            $this->{$function}($hasil_data);
            //$this->simpan_wilayah($ambil_data->dapodik);
        }
    }
    private function ambil_data($query, $data_sync){
        $response = Http::post('http://app.erapor-smk.net/api/sinkronisasi/'.$query, $data_sync);
        if($response->status() == 200){
            return $response->object();
        }
        dd($response);
        return false;
    }
    private function simpan_get_kd($count){
        $limit = 500;
        if($count > $limit){
            for ($counter = 0; $counter <= $count; $counter += $limit) {
                $ambil_data = $this->ambil_data('kd', ['offset' => $counter]);
                $this->simpan_kd($ambil_data->dapodik, $count, $counter);
                sleep(1);
            }
        }
    }
    private function simpan_kd($dapodik, $count, $page){
        $i=$page;//) ? $page * 500 : 1;
        $record['table'] = 'Referensi Kompetensi Dasar/Capaian Pembelajaran';
		$record['jumlah'] = $count;
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_kd($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync.json', json_encode($record));
            $i++;
        }
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
    private function simpan_wilayah($dapodik){
        $i=1;
        $record['table'] = 'Referensi Wilayah';
		$record['jumlah'] = count($dapodik);
		$record['inserted'] = $i;
		Storage::disk('public')->put('proses_sync.json', json_encode($record));
        foreach($dapodik as $data){
            $this->proses_wilayah($data);
            $record['inserted'] = $i;
			Storage::disk('public')->put('proses_sync.json', json_encode($record));
            $i++;
        }
    }
    private function proses_wilayah($wilayah){
        $data = Mst_wilayah::withTrashed()->updateOrCreate(
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
                'deleted_at' => $wilayah->deleted_at,
                'last_sync' => $wilayah->last_sync,
            ]
        );
        return $data;
    }
}
