<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Storage;
use Config;
class EraporController extends Controller
{
    public function index()
    {
        return view('content.dashboard')->with(['user' => auth()->user()]);
    }
    public function set_layout(Request $request){
        $request->session()->put('theme', str_replace('-layout', '', $request->layout));
    }
    public function dapodik()
    {
        return view('content.sinkronisasi.dapodik');
    }
    public function erapor()
    {
        return view('content.sinkronisasi.erapor');
    }
    public function nilai_dapodik()
    {
        return view('content.sinkronisasi.nilai-dapodik');
    }
    public function hitung(Request $request){
        $file = 'proses_sync_'.$request->route('sekolah_id').'.json';
        $json = NULL;
        if(Storage::disk('public')->exists($file)){
			$json = Storage::disk('public')->get($file);
		}
		$data = [
            'output' => json_decode($json),
            'json' => $json,
            'file' => $file,
        ];
        return response()->json($data);
    }
    public function settings(){
        return view('content.pengaturan.index');
    }
    public function users(Request $request){
        return view('content.pengaturan.users');
    }
    public function guru(){
        return view('content.referensi.guru');
    }
    public function tendik(){
        return view('content.referensi.tendik');
    }
    public function instruktur(){
        return view('content.referensi.instruktur');
    }
    public function asesor(){
        return view('content.referensi.asesor');
    }
    public function rombongan_belajar(){
        return view('content.referensi.rombongan-belajar');
    }
    public function rombel_pilihan(){
        return view('content.referensi.rombel-pilihan');
    }
    public function peserta_didik_aktif(){
        return view('content.referensi.peserta-didik-aktif');
    }
    public function peserta_didik_keluar(){
        return view('content.referensi.peserta-didik-keluar');
    }
    public function password_peserta_didik(){
        return view('content.referensi.password-peserta-didik');
    }
    public function mata_pelajaran(){
        return view('content.referensi.mata-pelajaran');
    }
    public function ekstrakurikuler(){
        return view('content.referensi.ekstrakurikuler');
    }
    public function teknik_penilaian(){
        return view('content.referensi.teknik-penilaian');
    }
    public function acuan_sikap(){
        return view('content.referensi.acuan-sikap');
    }
    public function kompetensi_dasar(){
        return view('content.referensi.kompetensi-dasar');
    }
    public function capaian_pembelajaran(){
        return view('content.referensi.capaian-pembelajaran');
    }
    public function tujuan_pembelajaran(){
        return view('content.referensi.tujuan-pembelajaran');
    }
    public function tambah_tujuan_pembelajaran(){
        return view('content.referensi.tambah-tp');
    }
    public function tambah_kompetensi_dasar(){
        return view('content.referensi.tambah-kompetensi-dasar');
    }
    public function tambah_capaian_pembelajaran(){
        return view('content.referensi.tambah-capaian-pembelajaran');
    }
    public function uji_kompetensi_keahlian(){
        return view('content.referensi.uji-kompetensi-keahlian');
    }
    public function dudi(){
        return view('content.referensi.dudi');
    }
    public function perencanaan_kurikulum_merdeka(){
        return view('content.perencanaan.kurikulum-merdeka');
    }
    public function perencanaan_projek_profil_pelajar_pancasila_dan_budaya_kerja(){
        return view('content.perencanaan.projek-profil-pelajar-pancasila-dan-budaya-kerja');
    }
    public function rasio_nilai_akhir(){
        return view('content.perencanaan.rasio-nilai-akhir');
    }
    public function perencanaan_pengetahuan(){
        return view('content.perencanaan.penilaian-pengetahuan');
    }
    public function perencanaan_keterampilan(){
        return view('content.perencanaan.penilaian-keterampilan');
    }
    public function bobot_keterampilan(){
        return view('content.perencanaan.bobot-keterampilan');
    }
    public function perencanaan_ukk(){
        return view('content.perencanaan.penilaian-ukk');
    }
    public function nilai_akhir(){
        return view('content.penilaian.nilai-akhir');
    }
    public function penilaian_kurikulum_merdeka(){
        return view('content.penilaian.kurikulum-merdeka');
    }
    public function penilaian_projek_profil_pelajar_pancasila_dan_budaya_kerja(){
        return view('content.penilaian.projek-profil-pelajar-pancasila-dan-budaya-kerja');
    }
    public function penilaian_pengetahuan(){
        return view('content.penilaian.pengetahuan');
    }
    public function penilaian_keterampilan(){
        return view('content.penilaian.keterampilan');
    }
    public function penilaian_sikap(){
        return view('content.penilaian.sikap');
    }
    public function tambah_penilaian_sikap(){
        return view('content.penilaian.tambah_sikap');
    }
    public function penilaian_remedial(){
        return view('content.penilaian.remedial');
    }
    public function penilaian_ukk(){
        return view('content.penilaian.ukk');
    }
    public function penilaian_ekstrakurikuler(){
        return view('content.penilaian.ekstrakurikuler');
    }
    public function capaian_kompetensi(){
        return view('content.penilaian.capaian-kompetensi');
    }
    public function nilai_us(){
        return view('content.laporan.nilai-us');
    }
    public function nilai_un(){
        return view('content.laporan.nilai-un');
    }
    public function kewirausahaan(){
        return view('content.laporan.kewirausahaan');
    }
    public function catatan_akademik(){
        return view('content.laporan.catatan-akademik');
    }
    public function nilai_karakter(){
        return view('content.laporan.nilai-karakter');
    }
    public function ketidakhadiran(){
        return view('content.laporan.ketidakhadiran');
    }
    public function nilai_ekstrakurikuler(){
        return view('content.laporan.nilai-ekstrakurikuler');
    }
    public function nilai_ekskul(){
        return view('content.wali-kelas.nilai-ekstrakurikuler');
    }
    public function catatan_sikap(){
        return view('content.wali-kelas.catatan-sikap');
    }
    public function pkl(){
        return view('content.laporan.pkl');
    }
    public function prestasi_pd(){
        return view('content.laporan.prestasi-pd');
    }
    public function kenaikan_kelas(){
        return view('content.laporan.kenaikan-kelas');
    }
    public function rapor_uts(){
        return view('content.laporan.rapor-uts');
    }
    public function rapor_semester(){
        return view('content.laporan.rapor-semester');
    }
    public function rapor_nilai_akhir(){
        return view('content.laporan.rapor-nilai-akhir');
    }
    public function projek_profil_pelajar_pancasila_dan_budaya_kerja(){
        return view('content.laporan.projek-profil-pelajar-pancasila-dan-budaya-kerja');
    }
    public function leger(){
        return view('content.laporan.leger');
    }
    public function leger_kurmer(){
        return view('content.laporan.leger-kurmer');
    }
    public function rekap_nilai(){
        return view('content.monitoring.rekap-nilai');
    }
    public function analisis_nilai(){
        return view('content.monitoring.analisis-nilai');
    }
    public function analisis_remedial(){
        return view('content.monitoring.analisis-remedial');
    }
    public function monitoring_capaian_kompetensi(){
        return view('content.monitoring.capaian-kompetensi');
    }
    public function prestasi_individu(){
        return view('content.monitoring.prestasi-individu');
    }
    public function unduhan(){
        return view('content.unduhan');
    }
    public function changelog(){
        return view('content.changelog');
    }
    public function check_update(){
        return view('content.check-update');
    }
    /* disabled by struktur kurikulum
    public function penilaian_pusat_keunggulan(){
        $breadcrumbs = [['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "SMK PK"]];
        return view('content.penilaian.pusat-keunggulan');
    }
    */
    /* disabled by struktur kurikulum
    public function perencanaan_pusat_keunggulan(){
        $breadcrumbs = [['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Penilaian SMK PK"]];
        return view('content.perencanaan.penilaian-pusat-keunggulan');
    }
    */
}
