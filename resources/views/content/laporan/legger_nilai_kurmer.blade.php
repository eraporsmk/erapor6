<table class="table" border="1">
	<thead>
		<tr>
			<th>No</th>
			<th>NAMA PESERTA DIDIK</th>
			<th>NISN</th>
			@foreach($all_pembelajaran as $pembelajaran)
			<th>{{$pembelajaran->nama_mata_pelajaran}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
	@foreach($data_siswa as $siswa)
		{{--dd($siswa->nilai_rapor_legger)--}}
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			@foreach($all_pembelajaran as $pembelajaran)
			<?php
			$nilai = $pembelajaran->nilai_akhir_kurmer()->where('anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->first();
			?>
			<td>{{($nilai) ? $nilai->nilai : '-'}}</td>
			@endforeach
		</tr>
	@endforeach
	</tbody>
</table>