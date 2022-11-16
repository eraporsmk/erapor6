<table>
	<thead>
		<tr>
			<th colspan="3">Template Import Tujuan Pembelajaran (TP)</th>
		</tr>
		<tr>
			<th colspan="2">Nama Mata Pelajaran</th>
			<th>{{$cp->pembelajaran->nama_mata_pelajaran}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode Mata Pelajaran</th>
			<th>{{$cp->pembelajaran->mata_pelajaran_id}}</th>
		</tr>
		<tr>
			<th colspan="2">Capaian Pembelajaran</th>
			<th>{{$cp->deskripsi}}</th>
		</tr>
		<tr>
			<th colspan="2">Kode CP</th>
			<th>{{$cp->cp_id}}</th>
		</tr>
		<tr>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th>No</th>
			<th colspan="2">Tujuan Pembelajaran</th>
		</tr>
	</thead>
	<tbody>
		@for($i=1; $i<11; $i++)
		<tr>
			<td>{{$i}}</td>
			<td colspan="2"></td>
		</tr>
		@endfor
	</tbody>
</table>