<div>
    <div wire:ignore.self class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Pratinjau Nilai {{($get_siswa) ? $get_siswa->peserta_didik->nama : '-'}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					@if($get_siswa)
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th style="vertical-align:middle;" align="center" rowspan="2">No</th>
								<th style="vertical-align:middle;" rowspan="2">Mata Pelajaran</th>
								<th align="center" class="text-center" rowspan="2">SKM</th>
								<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) {?>
								<th align="center" class="text-center">Nilai Akhir</th>
								<th align="center" class="text-center">Capaian Kompetensi</th>
								<?php } else { ?>
								<th colspan="2" align="center" class="text-center">Pengetahuan</th>
								<th colspan="2" align="center" class="text-center">Keterampilan</th>
								<?php } ?>
							</tr>
							<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') === false) {?>
							<tr>	
								<th align="center" class="text-center">Angka</th>
								<th align="center" class="text-center">Huruf</th>
								<th align="center" class="text-center">Angka</th>
								<th align="center" class="text-center">Huruf</th>
							</tr>
							<?php } ?>
						</thead>
						<tbody>
						<?php
						$all_pembelajaran = array();
						$get_pembelajaran = [];
						$set_pembelajaran = $get_siswa->rombongan_belajar->pembelajaran;//()->whereNotNull('kelompok_id')->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
						foreach($set_pembelajaran as $pembelajaran){
							if(in_array($pembelajaran->mata_pelajaran_id, mapel_agama())){
								if(filter_pembelajaran_agama($get_siswa->siswa->agama->nama, $pembelajaran->nama_mata_pelajaran)){
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
						$nilai_pengetahuan_value = ($pembelajaran->nilai_akhir_pengetahuan) ? $pembelajaran->nilai_akhir_pengetahuan->nilai : 0;
						$nilai_keterampilan_value = ($pembelajaran->nilai_akhir_keterampilan) ? $pembelajaran->nilai_akhir_keterampilan->nilai : 0;
						$nilai_akhir_pengetahuan	= $nilai_pengetahuan_value * $rasio_p;
						$nilai_akhir_keterampilan	= $nilai_keterampilan_value * $rasio_k;
						$nilai_akhir				= ($nilai_akhir_pengetahuan + $nilai_akhir_keterampilan) / 100;
						$nilai_akhir				= ($nilai_akhir) ? number_format($nilai_akhir,0) : 0;
						$nilai_akhir_pk				= ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0;
						$kkm = get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm);
						$produktif = array(4,5,9,10,13);
						if(in_array($pembelajaran->kelompok_id,$produktif)){
							$produktif = 1;
						} else {
							$produktif = 0;
						}
						//$get_mapel_agama = filter_agama_siswa($pembelajaran->pembelajaran_id, $pembelajaran->rombongan_belajar_id);
						$all_pembelajaran[$pembelajaran->kelompok->nama_kelompok][] = array(
							'deskripsi_mata_pelajaran' => $pembelajaran->deskripsi_mata_pelajaran,
							'nama_mata_pelajaran'	=> $pembelajaran->nama_mata_pelajaran,
							'kkm'	=> get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm),
							'nilai_akhir_pengetahuan'	=> ($pembelajaran->nilai_akhir_pengetahuan) ? $pembelajaran->nilai_akhir_pengetahuan->nilai : 0,
							'huruf_pengetahuan'	=> ($pembelajaran->nilai_akhir_pengetahuan) ? terbilang($pembelajaran->nilai_akhir_pengetahuan->nilai) : '-',
							'nilai_akhir_keterampilan'	=> ($pembelajaran->nilai_akhir_keterampilan) ? $pembelajaran->nilai_akhir_keterampilan->nilai : 0,
							'huruf_keterampilan'	=> ($pembelajaran->nilai_akhir_keterampilan) ? terbilang($pembelajaran->nilai_akhir_keterampilan->nilai) : '-',
							'nilai_akhir_pk' => ($pembelajaran->nilai_akhir_pk) ? $pembelajaran->nilai_akhir_pk->nilai : 0,
						);
						$i=1;
						?>
						@endforeach
						@foreach($all_pembelajaran as $kelompok => $data_pembelajaran)
						<?php 
						if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) { 
							$colspan = 5;
						} else { 
							$colspan = 7;
						} ?>
						<tr>
							<td colspan="{{$colspan}}"><b style="font-size: 13px;">{{$kelompok}}</b></td>
						</tr>
						@foreach($data_pembelajaran as $pembelajaran)
						<?php $pembelajaran = (object) $pembelajaran; ?>
							<tr>
								<td class="text-center" rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$i++}}</td>
								<td rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$pembelajaran->nama_mata_pelajaran}}</td>
								<td class="text-center" rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$pembelajaran->kkm}}</td>
								<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) { ?>
								<td class="text-center" rowspan="{{$pembelajaran->deskripsi_mata_pelajaran->count() + 1}}">{{$pembelajaran->nilai_akhir_pk}}</td>
								@if (!$pembelajaran->deskripsi_mata_pelajaran->count())
								<td class="text-center">-</td>
								@endif
								<?php } else { ?>
								<td class="text-center">{{$pembelajaran->nilai_akhir_pengetahuan}}</td>
								<td class="text-center">{{$pembelajaran->huruf_pengetahuan}}</td>
								<td class="text-center">{{$pembelajaran->nilai_akhir_keterampilan}}</td>
								<td class="text-center">{{$pembelajaran->huruf_keterampilan}}</td>
								<?php } ?>
							</tr>
							<?php if (strpos($get_siswa->rombongan_belajar->kurikulum->nama_kurikulum, 'Pusat') !== false) { ?>
								@foreach ($pembelajaran->deskripsi_mata_pelajaran as $deskripsi_mata_pelajaran)
								<?php
								//$deskripsi_mata_pelajaran = $get_siswa->deskripsi_mata_pelajaran()->where('pembelajaran_id', $pembelajaran->pembelajaran_id)->first();
								?>
								<tr>
									<td>{{($deskripsi_mata_pelajaran) ? $deskripsi_mata_pelajaran->deskripsi_pengetahuan : '-'}}</td>
								</tr>
								@endforeach
							<?php } ?>
							{{--dd($get_siswa)--}}
						@endforeach
						@endforeach
						</tbody>
					</table>
					@endif
				</div>
				<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:target="store" wire:loading.remove>Tutup</button>
                </div>
			</div>
		</div>
	</div>
</div>