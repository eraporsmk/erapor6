<div>
    <div wire:ignore.self class="modal fade" id="detilModal" tabindex="-1" aria-labelledby="detilModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detilModalLabel">Detil Data UKK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td width="30%">Kompetensi Keahlian</td>
                            <td width="70%">{{($paket_ukk) ? $paket_ukk->jurusan->nama_jurusan : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Kurikulum</td>
                            <td>{{($paket_ukk) ? $paket_ukk->kurikulum->nama_kurikulum : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Nomor Paket</td>
                            <td>{{($paket_ukk) ? $paket_ukk->nomor_paket : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Judul Paket (ID)</td>
                            <td>{{($paket_ukk) ? $paket_ukk->nama_paket_id : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Judul Paket (EN)</td>
                            <td>{{($paket_ukk) ? $paket_ukk->nama_paket_en : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{!! ($paket_ukk) ? status_label($paket_ukk->status) : '-' !!}</td>
                        </tr>
                        <tr>
                            <td>Unit UKK</td>
                            <td>
                                @if($paket_ukk)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="tex-center">Kode Unit</th>
                                            <th class="tex-center">Nama Unit Kompetensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paket_ukk->unit_ukk as $unit_ukk)
                                            <tr>
                                                <td>{{$unit_ukk->kode_unit}}</td>
                                                <td>{{$unit_ukk->nama_unit}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
