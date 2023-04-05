<?php // Code within app\Helpers\Helper.php
use App\Models\Peserta_didik;
use App\Models\Agama;
use App\Models\Pembelajaran;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;

function filter_agama_siswa($pembelajaran_id, $rombongan_belajar_id){
    $ref_agama = Agama::all();
	$agama_id = [];
	foreach ($ref_agama as $agama) {
        $nama_agama = str_replace('Budha', 'Buddha', $agama->nama);
        $agama_id[$agama->agama_id] = $nama_agama;
    }
    $get_mapel = Pembelajaran::with('mata_pelajaran')->find($pembelajaran_id);
    $nama_mapel = str_replace('Pendidikan Agama', '', $get_mapel->mata_pelajaran->nama);
    $nama_mapel = str_replace('KongHuChu', 'Konghuchu', $nama_mapel);
    $nama_mapel = str_replace('Kong Hu Chu', 'Konghuchu', $nama_mapel);
    $nama_mapel = str_replace('dan Budi Pekerti', '', $nama_mapel);
    $nama_mapel = str_replace('Pendidikan Kepercayaan terhadap', '', $nama_mapel);
    $nama_mapel = str_replace('Tuhan YME', 'Kepercayaan kpd Tuhan YME', $nama_mapel);
    $nama_mapel = trim($nama_mapel);
    $agama_id = array_search($nama_mapel, $agama_id);
    return $agama_id;
}
function mapel_agama(){
	return ['100014000', '100014140', '100015000', '100015010', '100016000', '100016010', '109011000', '109011010', '100011000', '100011070', '100013000', '100013010', '100012000', '100012050'];
}
function filter_pembelajaran_agama($agama_siswa, $nama_agama){
    $nama_agama = str_replace('Budha', 'Buddha', $nama_agama);
	$nama_agama = str_replace('Pendidikan Agama', '', $nama_agama);
	$nama_agama = str_replace('dan Budi Pekerti', '', $nama_agama);
	$nama_agama = str_replace('Pendidikan Kepercayaan', '', $nama_agama);
	$nama_agama = str_replace('terhadap', 'kpd', $nama_agama);
	$nama_agama = str_replace('KongHuChu', 'Konghuchu', $nama_agama);
	$nama_agama = str_replace('Kong Hu Chu', 'Konghuchu', $nama_agama);
	$nama_agama = trim($nama_agama);
	$agama_siswa = str_replace('KongHuChu', 'Konghuchu', $agama_siswa);
	$agama_siswa = str_replace('Kong Hu Chu', 'Konghuchu', $agama_siswa);
    $agama_siswa = str_replace('Kepercayaan ', '', $agama_siswa);
    if ($agama_siswa == $nama_agama) {
        return true;
    } else {
        return false;
    }
}
function pd_walas($with = NULL){
    $query = Peserta_didik::whereHas('anggota_rombel', function($query){
        $query->whereHas('rombongan_belajar', function($query){
            $query->where('jenis_rombel', 1);
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('guru_id', session('guru_id'));
        });
    })->with(['anggota_rombel' => function($query) use ($with){
        $query->whereHas('rombongan_belajar', function($query){
            $query->where('jenis_rombel', 1);
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('guru_id', session('guru_id'));
        });
        if($with){
            $query->with($with);
        }
    }]);
    $query->orderBy('nama');
    return $query->get();
}
function jenis_gtk($query){
    $data['tendik'] = array(11, 30, 40, 41, 42, 43, 44, 57, 58, 59);
    $data['guru'] = array(3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 20, 25, 26, 51, 52, 53, 54, 56);
    $data['instruktur'] = array(97);
    $data['asesor'] = array(98);
    return collect($data[$query]);
}
function bilangan_bulat($angka){
    return number_format($angka, 0);
}
function nilai_ekskul($nilai){
    $predikat = [
        1 => 'Sangat Baik',
        2 => 'Baik',
        3 => 'Cukup',
        4 => 'Kurang',
    ];
    return $predikat[$nilai];
}
function terbilang($angka){
    if($angka){
        return ucwords(Terbilang::make(number_format($angka,0)));
    }
}
function status_penilaian(){
    return config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.status_penilaian');
}
function check_walas(){
    if(auth()->user()->hasRole('wali', session('semester_id'))){
        return TRUE;
    } else {
        return FALSE;
    }
}
function check_2018(){
	$tahun = substr(session('semester_aktif'), 0, 4);
	if ($tahun >= 2018) {
        return true;
    } else {
        return false;
    }
}
function predikat($kkm, $nilai, $produktif = NULL){
    if ($produktif) {
        $result = array(
            'A+'	=> 100, // 95 - 100
            'A'		=> 94, // 90 - 94
            'A-'	=> 89, // 85 - 89
            'B+'	=> 84, // 80 - 84
            'B'		=> 79, // 75 - 79
            'B-'	=> 74, // 70 - 74
            'C'		=> 69, // 65 - 69
            'D'		=> 64, // 0 - 59
        );
    } else {
        $result = array(
            'A+'	=> 100, // 95 - 100
            'A'		=> 94, // 90 - 94
            'A-'	=> 89, // 85 - 89
            'B+'	=> 84, // 80 - 84
            'B'		=> 79, // 75 - 79
            'B-'	=> 74, // 70 - 74
            'C'		=> 69, // 60 - 69
            'D'		=> 59, // 0 - 59
        );
    }
    if ($result[$nilai] > 100)
        $result[$nilai] = 100;
    return $result[$nilai];
}
function konversi_huruf($kkm, $nilai, $produktif = NULL){
    $check_2018 = check_2018();
    if ($check_2018) {
        $show = 'predikat';
        $a = predikat($kkm, 'A') + 1;
        $a_min = predikat($kkm, 'A-') + 1;
        $b_plus = predikat($kkm, 'B+') + 1;
        $b = predikat($kkm, 'B') + 1;
        $b_min = predikat($kkm, 'B-') + 1;
        $c = predikat($kkm, 'C') + 1;
        $d = predikat($kkm, 'D', $produktif) + 1;
        if ($nilai == 0) {
            $predikat 	= '-';
        } elseif ($nilai >= $a) { //$settings->a_min){ //86
            $predikat 	= 'A+';
        } elseif ($nilai >= $a_min) { //$settings->a_min){ //86
            $predikat 	= 'A';
        } elseif ($nilai >= $b_plus) { //$settings->a_min){ //86
            $predikat 	= 'A-';
        } elseif ($nilai >= $b) { //$settings->a_min){ //86
            $predikat 	= 'B+';
        } elseif ($nilai >= $b_min) { //$settings->a_min){ //86
            $predikat 	= 'B';
        } elseif ($nilai >= $c) { //$settings->a_min){ //86
            $predikat 	= 'B-';
        } elseif ($nilai >= $d) { //$settings->a_min){ //86
            $predikat 	= 'C';
        } elseif ($nilai < $d) { //$settings->a_min){ //86
            $predikat 	= 'D';
        }
    } else {
        $b = predikat($kkm, 'b') + 1;
        $c = predikat($kkm, 'c') + 1;
        $d = predikat($kkm, 'd') + 1;
        if ($n == 0) {
            $predikat 	= '-';
            $sikap		= '-';
            $sikap_full	= '-';
        } elseif ($n >= $b) { //$settings->a_min){ //86
            $predikat 	= 'A';
            $sikap		= 'SB';
            $sikap_full	= 'Sangat Baik';
        } elseif ($n >= $c) { //71
            $predikat 	= 'B';
            $sikap		= 'B';
            $sikap_full	= 'Baik';
        } elseif ($n >= $d) { //56
            $predikat 	= 'C';
            $sikap		= 'C';
            $sikap_full	= 'Cukup';
        } elseif ($n < $d) { //56
            $predikat 	= 'D';
            $sikap		= 'K';
            $sikap_full	= 'Kurang';
        }
    }
    return $predikat;		
}
function status_kenaikan($status){
    if ($status == 1) {
        $status_teks = 'Naik ke kelas';
    } elseif ($status == 2) {
        $status_teks = 'Tetap dikelas';
    } elseif ($status == 3) {
        $status_teks = 'Lulus';
    } else {
        $status_teks = 'Tidak Lulus';
    }
    return $status_teks;
}
function warna_dimensi($id){
    $data = [
        1 => 'primary',
        2 => 'success',
        3 => 'danger',
        4 => 'warning',
        5 => 'info',
        6 => 'secondary'
    ];
    return $data[$id];
}
function opsi_budaya($n)
{
    if (!$n) {
        $predikat 	= '-';
    } elseif ($n >= 4) {
        $predikat 	= '<span class="badge bg-green">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 3) {
        $predikat 	= '<span class="badge bg-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 2) {
        $predikat 	= '<span class="badge bg-blue">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    } elseif ($n >= 1) {
        $predikat 	= '<span class="badge bg-yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    }
    return $predikat;
}
function get_kkm($kelompok_id, $kkm)
{
    if ($kkm) {
        return $kkm;
    }
    $check_2018 = check_2018();
    if ($check_2018) {
        $produktif = array(4, 5, 9, 10, 13);
        $non_produktif = array(1, 2, 3, 6, 7, 8, 11, 12, 99);
        if (in_array($kelompok_id, $produktif)) {
            $new_kkm = 65;
        } elseif (in_array($kelompok_id, $non_produktif)) {
            $new_kkm = 60;
        } else {
            $new_kkm = $kkm;
        }
    }
    return $new_kkm;
}
function status_label($status)
{
    if ($status == '1') :
        $label = '<span class="btn btn-sm btn-success"> Aktif </span>';
    elseif ($status == '0') :
        $label = '<span class="btn btn-sm btn-danger"> Non Aktif </span>';
    endif;
    return $label;
}
function keterangan_ukk($n, $lang = 'ID')
{
    if ($lang == 'ID') {
        if (!$n) {
            $predikat 	= '';
        } elseif ($n >= 90) {
            $predikat 	= 'Sangat Kompeten';
        } elseif ($n >= 75 && $n <= 89) {
            $predikat 	= 'Kompeten';
        } elseif ($n >= 70 && $n <= 74) {
            $predikat 	= 'Cukup Kompeten';
        } elseif ($n < 70) {
            $predikat 	= 'Belum Kompeten';
        }
    } else {
        if (!$n) {
            $predikat 	= '';
        } elseif ($n >= 90) {
            $predikat 	= 'Highly Competent';
        } elseif ($n >= 75 && $n <= 89) {
            $predikat 	= 'Competent';
        } elseif ($n >= 70 && $n <= 74) {
            $predikat 	= 'Partly Competent';
        } elseif ($n < 70) {
            $predikat 	= 'Not Yet Competent';
        }
    }
    return $predikat;
}
function prepare_send($str){
    return rawurlencode(base64_encode(gzcompress(encryptor(serialize($str)))));
}
function prepare_receive($str){
    return unserialize(decryptor(gzuncompress(base64_decode(rawurldecode($str)))));
}
function encryptor($str){
    return $str;
}
function decryptor($str){
    return $str;
}
function clean($string){
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
function get_previous_letter($string)
{
    $last = substr($string, -1);
    $part = substr($string, 0, -1);
    if (strtoupper($last) == 'A') {
        $l = substr($part, -1);
        if ($l == 'A') {
            return substr($part, 0, -1) . "Z";
        }
        return $part . chr(ord($l) - 1);
    } else {
        return $part . chr(ord($last) - 1);
    }
}
function sebaran($input, $a, $b)
{
    $range_data = range($a, $b);
    $output = array_intersect($input, $range_data);
    return $output;
}
function sebaran_tooltip($input, $a, $b, $c)
{
    $range_data = range($a, $b);
    $output = array_intersect($input, $range_data);
    $data = array();
    $nama_siswa = '';
    foreach ($output as $k => $v) {
        $data[] = $k;
    }
    if (count($output) == 0) {
        $result = count($output);
    } else {
        //$result = '<a class="tooltip-' . $c . '" href="javascript:void(0)" title="' . implode('<br />', $data) . '" data-html="true">' . count($output) . '</a>';
        $result = '<a data-bs-toggle="tooltip" data-bs-placement="' . $c . '" data-bs-html="true" href="javascript:void(0)" title="' . implode('<br />', $data) . '">' . count($output) . '</a>';
    }
    return $result;
}
function table_striped(){
    if(session('theme') !== 'dark'){
        return 'table-striped';
    }
    return '';
}
function tingkat_kelas($kelas_10, $kelas_11, $kelas_12, $kelas_13){
    $data = collect([
        ['kelas' => $kelas_10, 'tingkat' => 10],
        ['kelas' => $kelas_11, 'tingkat' => 11], 
        ['kelas' => $kelas_12, 'tingkat' => 12], 
        ['kelas' => $kelas_13, 'tingkat' => 13]
    ]);
    $filtered = $data->filter(function ($value, $key) {
        //dump($key);
        //dump($value['kelas']);
        return $value['kelas'] > 0;
    });
    //dd($filtered->all());
    return $filtered->implode('tingkat', ', ');
}
function table_sync(){
    return [
        'ref.paket_ukk',
        'ref.kompetensi_dasar',
        'ref.capaian_pembelajaran',
        'users',
        'unit_ukk',
        'tujuan_pembelajaran',
        'tp_nilai',
        'sekolah',
        'rombongan_belajar',
        'rombel_4_tahun',
        'rencana_ukk',
        'rencana_penilaian',
        'rencana_budaya_kerja',
        'rapor_pts',
        'ptk_keluar',
        'prestasi',
        'prakerin',
        'peserta_didik',
        'pembelajaran',
        'pd_keluar',
        'nilai_us',
        'nilai_un',
        'nilai_ukk',
        'nilai_tp',
        'nilai_sumatif',
        'nilai_sikap',
        'nilai_remedial',
        'nilai_rapor',
        'nilai_karakter',
        'nilai_ekstrakurikuler',
        'nilai_budaya_kerja',
        'nilai_akhir',
        'nilai',
        'mou',
        'kewirausahaan',
        'kenaikan_kelas',
        'kd_nilai',
        'jurusan_sp',
        'guru',
        'gelar_ptk',
        'ekstrakurikuler',
        'dudi',
        'deskripsi_sikap',
        'deskripsi_mata_pelajaran',
        'catatan_wali',
        'catatan_ppk',
        'catatan_budaya_kerja',
        'bobot_keterampilan',
        'bimbing_pd',
        'aspek_budaya_kerja',
        'asesor',
        'anggota_rombel',
        'anggota_kewirausahaan',
        'anggota_akt_pd',
        'akt_pd',
        'absensi',
    ];
}
function get_table($table, $sekolah_id, $tahun_ajaran_id, $semester_id, $count = NULL){
    $request = DB::table($table)->where(function($query) use ($table, $sekolah_id, $tahun_ajaran_id, $semester_id){
        if(in_array($table, ['ref.kompetensi_dasar'])){
            $query->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('users')
                      ->whereColumn('ref.kompetensi_dasar.user_id', 'users.user_id');
            });
            $query->whereRaw('updated_at > last_sync');
        }
        if(in_array($table, ['ref.paket_ukk', 'users']) || Schema::hasColumn($table, 'sekolah_id')){
            $query->where('sekolah_id', $sekolah_id);
            $query->whereRaw('updated_at > last_sync');
        }
        if(in_array($table, ['ref.capaian_pembelajaran'])){
            $query->where('is_dir', 0);
            $query->whereRaw('updated_at > last_sync');
        }
        if (Schema::hasColumn($table, 'tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        }
        if (Schema::hasColumn($table, 'semester_id')) {
            $query->where('semester_id', $semester_id);
        }
        if (Schema::hasColumn($table, 'last_sync')) {
            $query->whereRaw('updated_at > last_sync');
        }
    });
    if($count){
        return $request->count();
    } else {
        return $request->get();
    }
}
function nama_table($table){
    $data = str_replace('_', ' ', $table);
    $data = str_replace('ref.', '', $data);
    return ucwords($data);
}
function http_client($satuan, $data_sync){
    $response = Http::withOptions([
        'verify' => false,
        'debug' => (session('user_id')) ? FALSE : config('app.debug'),
    ])->withHeaders([
        'x-api-key' => $data_sync['sekolah_id'],
    ])->retry(3, 100)->post('http://app.erapor-smk.net/api/sync/dapodik/'.$satuan, $data_sync);
    return $response->object();
}
function merdeka($nama_kurikulum){
    return Str::contains($nama_kurikulum, 'Merdeka');
}