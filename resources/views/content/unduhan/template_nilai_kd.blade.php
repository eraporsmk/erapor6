<table>
	<thead>
		<tr>
			<th>No</th>
			<th>PD_ID</th>
			<th>Nama Peserta Didik</th>
			<th>NISN</th>
			@foreach ($kd_nilai as $kd)
            <th>'{{$kd->id_kompetensi}}</th>    
            @endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($data_siswa as $siswa)
		<tr>
			<td>{{$loop->iteration}}</td>
			<td>{{$siswa->anggota_rombel->anggota_rombel_id}}</td>
			<td>{{$siswa->nama}}</td>
			<td>{{$siswa->nisn}}</td>
			@foreach($siswa->anggota_rombel->nilai_kd as $nilai_kd)
                <td>
					{{($nilai_kd) ? $nilai_kd->nilai : 0}}
				</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>