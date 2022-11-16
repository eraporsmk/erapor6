<table>
	<thead>
		<tr>
			<th>No</th>
			<th>PD_ID</th>
			<th>Nama Peserta Didik</th>
			<th>NISN</th>
			<th>Nilai Akhir</th>
			<th>Kompetensi yang sudah dicapai</th>
			<th>Kompetensi yang perlu ditingkatkan</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			<td>{{($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : NULL}}</td>
			<td>{{($siswa->anggota_rombel->single_deskripsi_mata_pelajaran) ? $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan : NULL}}</td>
			<td>{{($siswa->anggota_rombel->single_deskripsi_mata_pelajaran) ? $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_keterampilan : NULL}}</td>
		</tr>
		@endforeach
	</tbody>
</table>