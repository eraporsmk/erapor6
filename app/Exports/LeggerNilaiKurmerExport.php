<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;

class LeggerNilaiKurmerExport implements FromView, ShouldAutoSize
{
    use Exportable;
    public function query(string $rombongan_belajar_id, $merdeka)
    {
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->merdeka = $merdeka;
        return $this;
    }
	public function view(): View
    {
        $data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
		//$get_siswa = Anggota_rombel::with('siswa')->where('rombongan_belajar_id', $this->rombongan_belajar_id)->order()->get();
		$all_pembelajaran = Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->whereNotNull('kelompok_id')->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
		$params = array(
			'data_siswa' => $data_siswa,
			'all_pembelajaran'	=> $all_pembelajaran,
            'rombongan_belajar' => Rombongan_belajar::with(['sekolah'])->find($this->rombongan_belajar_id),
            'merdeka' => $this->merdeka,
		);
		return view('content.laporan.legger_nilai_kurmer', $params);
    }
}
