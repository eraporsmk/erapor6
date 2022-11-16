@extends('layouts.cetak')
@section('content')
@if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') == false)
<table border="0" width="100%">
	<tr>
    	<td style="width: 25%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 1%;" class="text-center">:</td>
		<td style="width: 74%">{{strtoupper($get_siswa->peserta_didik->nama)}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Kelas</td>
		<td class="text-center">:</td>
		<td>{{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td class="text-center">:</td>
		<td>{{str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Semester</td>
		<td class="text-center">:</td>
		<td>{{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
</table>
@else
<table border="0" width="100%">
	<tr>
    	<td style="width: 20%;padding:0px;">Nama Peserta Didik</td>
		<td style="width: 50%">: {{strtoupper($get_siswa->peserta_didik->nama)}}</td>
		<td style="padding:0px;width: 15%">Kelas</td>
		<td style="width: 15%">: {{$get_siswa->rombongan_belajar->nama}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Nomor Induk/NISN</td>
		<td>: {{$get_siswa->peserta_didik->no_induk.' / '.$get_siswa->peserta_didik->nisn}}</td>
		<td style="padding:0px;">Fase</td>
		<td>: {{($get_siswa->rombongan_belajar->tingkat == 10) ? 'E' : 'F'}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Sekolah</td>
		<td>: {{$get_siswa->rombongan_belajar->sekolah->nama}}</td>
		<td style="padding:0px;">Semester</td>
		<td>: {{substr($get_siswa->rombongan_belajar->semester->nama,10)}}</td>
	</tr>
	<tr>
		<td style="padding:0px;">Alamat</td>
		<td>: {{$get_siswa->peserta_didik->alamat}}</td>
		<td style="padding:0px;">Tahun Pelajaran</td>
		<td>: {{str_replace('/','-',substr($get_siswa->rombongan_belajar->semester->nama,0,9))}}</td>
	</tr>
</table>
@endif
<br />
@if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') == false)
<div class="strong"><strong>A.&nbsp;&nbsp;Nilai Akademik</strong></div>
@endif
<table class="table table-bordered" border="1">
	<thead>
		<tr>
			<th style="vertical-align:middle;width: 2px;" class="text-center">No</th>
			<th style="vertical-align:middle;width: 250px;">Mata Pelajaran</th>
			<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') !== false) {?>
			<th style="vertical-align:middle;width: 50px;" class="text-center">Nilai Akhir</th>
			<th style="vertical-align: middle;" class="text-center">Capaian Kompetensi</th>
			<?php } else { ?>
			<th style="vertical-align: middle;" class="text-center">Pengetahuan</th>
			<th style="vertical-align: middle;" class="text-center">Keterampilan</th>
			<th style="vertical-align: middle;" class="text-center">Nilai Akhir</th>
			<th style="vertical-align: middle;" class="text-center">Predikat</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php
	$all_pembelajaran = array();
	$all_pembelajaran_pilihan = array();
	$get_pembelajaran = [];
	$get_pembelajaran_pilihan = [];
	$set_pembelajaran = $get_siswa->rombongan_belajar->pembelajaran;
	foreach($set_pembelajaran as $pembelajaran){
		//$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
			if(filter_pembelajaran_agama($get_siswa->peserta_didik->agama->nama, $pembelajaran->mata_pelajaran->nama)){
				$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
			}
		} else {
			$get_pembelajaran[$pembelajaran->pembelajaran_id] = $pembelajaran;
		}
	}
	?>
	@foreach($get_pembelajaran as $pembelajaran)
	<?php
	$rasio_p = ($pembelajaran->rasio_p) ? $pembelajaran->rasio_p : 50;
	$rasio_k = ($pembelajaran->rasio_k) ? $pembelajaran->rasio_k : 50;
	$nilai_pengetahuan_value = ($pembelajaran->nilai_akhir_kurmer) ? $pembelajaran->nilai_akhir_kurmer->nilai : 0;
	$nilai_keterampilan_value = ($pembelajaran->nilai_akhir_keterampilan) ? $pembelajaran->nilai_akhir_keterampilan->nilai : 0;
	$nilai_akhir_pengetahuan	= $nilai_pengetahuan_value * $rasio_p;
	$nilai_akhir_keterampilan	= $nilai_keterampilan_value * $rasio_k;
	$nilai_akhir				= ($nilai_akhir_pengetahuan + $nilai_akhir_keterampilan) / 100;
	$nilai_akhir				= ($nilai_akhir) ? number_format($nilai_akhir,0) : 0;
	$nilai_akhir				= ($pembelajaran->nilai_akhir) ? $pembelajaran->nilai_akhir->nilai : 0;
	$kkm = $pembelajaran->skm;
	$produktif = array(4,5,9,10,13);
	if(in_array($pembelajaran->kelompok_id,$produktif)){
		$produktif = 1;
	} else {
		$produktif = 0;
	}
	$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = array(
		'deskripsi_mata_pelajaran' => $pembelajaran->deskripsi_mata_pelajaran,
		'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
		'nilai_akhir_pengetahuan'	=> $nilai_pengetahuan_value,
		'nilai_akhir_keterampilan'	=> $nilai_keterampilan_value,
		'nilai_akhir'	=> $nilai_akhir,
		'predikat'	=> konversi_huruf($kkm, $nilai_akhir, $produktif),
		//'nilai_akhir_pk' => ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0,
		'nilai_akhir_pk' => $nilai_pengetahuan_value,
	);
	$i=1;
	?>
	@endforeach
	<?php 
	if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') !== false) { 
		$colspan = 4;
	} else { 
		$colspan = 6;
	} ?>
	@foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
	@if($kelompok == 'C1. Dasar Bidang Keahlian' || $kelompok == 'C3. Kompetensi Keahlian')
	<tr>
		<td colspan="6" class="strong"><strong style="font-size: 13px;">C. Muatan Peminatan Kejuruan</strong></td>
	</tr>
	@endif
	<tr>
		<td colspan="{{$colspan}}" class="strong"><strong style="font-size: 13px;">{{$kelompok}}</strong></td>
	</tr>
	@foreach($data_pembelajaran as $pembelajaran)
	<?php 
	$pembelajaran = (object) $pembelajaran; 
	$rowspan = 1;
	if($pembelajaran->deskripsi_mata_pelajaran->count()){
		$rowspan = $pembelajaran->deskripsi_mata_pelajaran->count() + 2;
	}
	?>
		<tr>
			<td class="text-center" rowspan="{{$rowspan}}" style="vertical-align:middle;">{{$i++}}</td>
			<td rowspan="{{$rowspan}}" style="vertical-align:middle;">{{$pembelajaran->nama_mata_pelajaran}}</td>
			<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') !== false) {?>
			<td class="text-center" rowspan="{{$rowspan}}" style="vertical-align:middle;">{{$pembelajaran->nilai_akhir}}</td>
			@if (!$pembelajaran->deskripsi_mata_pelajaran->count())
			<td class="text-center" style="vertical-align:middle;">-</td>
			@endif
			<?php } else { ?>
			<td class="text-center">{{$pembelajaran->nilai_akhir_pengetahuan}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir_keterampilan}}</td>
			<td class="text-center">{{$pembelajaran->nilai_akhir}}</td>
			<td class="text-center">{{$pembelajaran->predikat}}</td>
			<?php } ?>
		</tr>
		<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') !== false) { ?>
			@foreach ($pembelajaran->deskripsi_mata_pelajaran as $deskripsi_mata_pelajaran)
			<tr>
				<td>{!! ($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_pengetahuan : '-' !!}</td>
			</tr>
			<tr>
				<td>{!! ($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_keterampilan : '-' !!}</td>
			</tr>
			@endforeach
		<?php } ?>
	@endforeach
	@endforeach
	@if($find_anggota_rombel_pilihan)
	@foreach($find_anggota_rombel_pilihan->rombongan_belajar->pembelajaran as $pembelajaran)
	<?php
	$rowspan = 1;
	if($pembelajaran->deskripsi_mata_pelajaran->count()){
		$rowspan = $pembelajaran->deskripsi_mata_pelajaran->count() + 2;
	}
	?>
	<tr>
		<td rowspan="{{$rowspan}}" class="text-center" style="vertical-align:middle;">{{isset($i) ? $i : 1}}</td>
		<td rowspan="{{$rowspan}}" style="vertical-align:middle;">{{$pembelajaran->nama_mata_pelajaran}}</td>
		<td rowspan="{{$rowspan}}" class="text-center" style="vertical-align:middle;">{{($pembelajaran->nilai_akhir_kurmer) ? $pembelajaran->nilai_akhir_kurmer->nilai : 0}}</td>
	</tr>
	@foreach ($pembelajaran->deskripsi_mata_pelajaran as $deskripsi_mata_pelajaran)
	<tr>
		<td>{!! ($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_pengetahuan : '-' !!}</td>
	</tr>
	<tr>
		<td>{!! ($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_keterampilan : '-' !!}</td>
	</tr>
	@endforeach
	@endforeach
	@endif
	</tbody>
</table>
<br />
@if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka') !== false)
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="vertical-align: middle; width: 5%;" class="text-center">No</th>
			<th style="vertical-align: middle; width: 35%;" class="text-center">Kegiatan Ekstrakurikuler</th>
			<th style="vertical-align: middle; width: 60%;" class="text-center">Keterangan</th>
		</tr>
	</thead>
	<tbody>
	@if($get_siswa->all_nilai_ekskul->count())
	@foreach($get_siswa->all_nilai_ekskul as $nilai_ekskul)
		<tr>
			<td style="vertical-align: middle;">{{$loop->iteration}}</td>
			<td>{{strtoupper($nilai_ekskul->rombongan_belajar->nama)}}</td>
			<td>{{$nilai_ekskul->deskripsi_ekskul}}</td>
		</tr>
	@endforeach
	@else
		<tr>
			<td class="text-center" colspan="3">&nbsp;</td>
		</tr>
	@endif
	</tbody>
</table>
<br />
<table class="table table-bordered" style="width: 300px;">
	<thead>
		<tr>
			<th colspan="2" class="text-center">Ketidakhadiran</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="100">Sakit</td>
			<td> : {{($get_siswa->kehadiran) ? ($get_siswa->kehadiran->sakit) ? $get_siswa->kehadiran->sakit.' hari' : '- hari' : '.... hari'}}</td>
		</tr>
		<tr>
			<td>Izin</td>
			<td width="100"> : {{($get_siswa->kehadiran) ? ($get_siswa->kehadiran->izin) ? $get_siswa->kehadiran->izin.' hari' : '- hari' : '.... hari'}}</td>
		</tr>
		<tr>
			<td>Tanpa Keterangan</td>
			<td> : {{($get_siswa->kehadiran) ? ($get_siswa->kehadiran->alpa) ? $get_siswa->kehadiran->alpa.' hari' : '- hari' : '.... hari'}}</td>
		</tr>
	</tbody>
</table>
<table width="100%">
	<tr>
	  <td style="width:40%">
		  <p>Orang Tua/Wali</p><br>
  <br>
  <br>
  <br>
  <br>
  <br>
		  <p>...................................................................</p>
	  </td>
	  <td style="width:20%"></td>
	  <td style="width:40%"><p>{{$get_siswa->peserta_didik->sekolah->kabupaten}}, {{$tanggal_rapor}}<br>Wali Kelas</p><br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <p>
  <u>{{$get_siswa->rombongan_belajar->wali_kelas->nama_lengkap }}</u><br />
  NIP. {{$get_siswa->rombongan_belajar->wali_kelas->nip}}
  </td>
	</tr>
  </table>
  <table width="100%" style="margin-top:10px;">
	<tr>
		<td style="width:30%">&nbsp;</td>
	  <td style="font-family: Tahoma; font-size: 13px;">
		  	<p>Mengetahui,<br>Kepala Sekolah</p>
	  		<br>
			<br>
			<br>
			<br>
			<br>
			<p><u>{{$get_siswa->peserta_didik->sekolah->kepala_sekolah->nama_lengkap}}</u><br />
			NIP. {{$get_siswa->peserta_didik->sekolah->kepala_sekolah->nip}}
			</p>
	  </td>
	  <td style="width:30%">&nbsp;</td>
	</tr>
  </table>
@else
<div class="strong"><strong>B.&nbsp;&nbsp;Catatan Akademik</strong></div>
<table width="100%" class="table table-bordered">
  <tr>
    <td style="padding:10px;">{!! ($get_siswa->single_catatan_wali) ? $get_siswa->single_catatan_wali->uraian_deskripsi : '' !!}</td>
  </tr>
</table>
@endif
@endsection