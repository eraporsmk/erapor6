<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_penilaian;
use App\Models\Capaian_pembelajaran;
use App\Exports\LeggerKDExport;
use App\Exports\LeggerNilaiAkhirExport;
use App\Exports\LeggerNilaiRaporExport;
use App\Exports\AbsensiExport;
use App\Exports\TemplateNilaiAkhir;
use App\Exports\TemplateNilaiKd;
use App\Exports\TemplateNilaiTp;
use App\Exports\TemplateTp;
use App\Exports\LeggerNilaiKurmerExport;

class UnduhanController extends Controller
{
    public function unduh_leger_kd(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Otentik Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerKDExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
    public function unduh_leger_nilai_akhir(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Nilai Akhir Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiAkhirExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
	public function unduh_leger_nilai_kurmer(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Nilai Akhir Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiKurmerExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
    public function unduh_leger_nilai_rapor(){
        $rombongan_belajar = Rombongan_belajar::find(request()->route('rombongan_belajar_id'));
		$nama_file = 'Leger Nilai Rapor Kelas ' . $rombongan_belajar->nama;
		$nama_file = clean($nama_file);
		$nama_file = $nama_file . '.xlsx';
		return (new LeggerNilaiRaporExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
    }
	public function template_nilai_akhir(){
		if(request()->route('pembelajaran_id')){
			$pembelajaran = Pembelajaran::find(request()->route('pembelajaran_id'));
			$nama_file = 'Template Nilai Akhir Mata Pelajaran ' . $pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			return (new TemplateNilaiAkhir)->query(request()->route('pembelajaran_id'), $pembelajaran->rombongan_belajar_id)->download($nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_nilai_kd(){
		if(request()->route('rencana_penilaian_id')){
			$rencana_penilaian = Rencana_penilaian::with(['pembelajaran'])->find(request()->route('rencana_penilaian_id'));
			$nama_file = 'Template Nilai KD '.$rencana_penilaian->nama_penilaian.' Mata Pelajaran ' . $rencana_penilaian->pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			return (new TemplateNilaiKd)->query(request()->route('rencana_penilaian_id'), $rencana_penilaian->pembelajaran->rombongan_belajar_id)->download($nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_nilai_tp(){
		if(request()->route('rencana_penilaian_id')){
			$rencana_penilaian = Rencana_penilaian::with(['pembelajaran'])->find(request()->route('rencana_penilaian_id'));
			$nama_file = 'Template Nilai TP '.$rencana_penilaian->nama_penilaian.' Mata Pelajaran ' . $rencana_penilaian->pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			return (new TemplateNilaiTp)->query(request()->route('rencana_penilaian_id'), $rencana_penilaian->pembelajaran->rombongan_belajar_id)->download($nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
	public function template_tp(){
		if(request()->route('cp_id')){
			$cp = Capaian_pembelajaran::with(['pembelajaran'])->find(request()->route('cp_id'));
			$nama_file = 'Template TP '.$cp->elemen.' Mata Pelajaran ' . $cp->pembelajaran->nama_mata_pelajaran;
			$nama_file = clean($nama_file);
			$nama_file = $nama_file . '.xlsx';
			return (new TemplateTp)->query(request()->route('cp_id'))->download($nama_file);
		} else {
			echo 'Akses tidak sah!';
		}
	}
}
