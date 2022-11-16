<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;

class TemplateNilaiAkhir implements FromView, ShouldAutoSize
{
    use Exportable;
    public function query(string $pembelajaran_id, string $rombongan_belajar_id)
    {
        $this->pembelajaran_id = $pembelajaran_id;
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        return $this;
    }
	public function view(): View
    {
        $data_siswa = Peserta_didik::where(function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            });
        })->with([
            'anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with([
                    'nilai_akhir_mapel' => function($query){
                        $query->where('kompetensi_id', 1);
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'single_deskripsi_mata_pelajaran' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    }
                ]);
            }
        ])->orderBy('nama')->get();
        $params = array(
			'data_siswa' => $data_siswa,
		);
        return view('content.unduhan.template_nilai_akhir', $params);
    }
}
