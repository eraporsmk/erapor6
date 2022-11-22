<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EraporController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\UnduhanController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::namespace('Auth')->group(function () {
    Route::get('/login',[LoginController::class, 'show_login_form'])->name('login');
    Route::post('/login',[LoginController::class, 'process_login'])->name('process_login');
    if(config('erapor.registration')){
        Route::get('/register',[LoginController::class, 'show_signup_form'])->name('register');
        Route::post('/register',[LoginController::class, 'process_signup']);
    }
    Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', [EraporController::class, 'index'])->name('index');
    Route::post('/set-layout', [EraporController::class, 'set_layout'])->name('set_layout');
    Route::group(['prefix' => 'sinkronisasi'], function(){
        Route::get('/dapodik', [EraporController::class, 'dapodik'])->name('sinkronisasi.dapodik');
        Route::get('/erapor', [EraporController::class, 'erapor'])->name('sinkronisasi.erapor');
        Route::get('/nilai-dapodik', [EraporController::class, 'nilai_dapodik'])->name('sinkronisasi.nilai_dapodik');
    });
    // Route Components
    Route::group(['prefix' => 'setting'], function(){
        Route::get('/umum', [EraporController::class, 'settings'])->name('setting.index');
        Route::get('/users', [EraporController::class, 'users'])->name('setting.users');
    });
    Route::group(['prefix' => 'referensi'], function(){
        Route::get('/guru', [EraporController::class, 'guru'])->name('referensi.guru');
        Route::get('/tendik', [EraporController::class, 'tendik'])->name('referensi.tendik');
        Route::get('/instruktur', [EraporController::class, 'instruktur'])->name('referensi.instruktur');
        Route::get('/asesor', [EraporController::class, 'asesor'])->name('referensi.asesor');
        Route::get('/rombongan-belajar', [EraporController::class, 'rombongan_belajar'])->name('referensi.rombongan-belajar');
        Route::get('/rombel-pilihan', [EraporController::class, 'rombel_pilihan'])->name('referensi.rombel-pilihan');
        Route::get('/peserta-didik-aktif', [EraporController::class, 'peserta_didik_aktif'])->name('referensi.peserta-didik-aktif');
        Route::get('/peserta-didik-keluar', [EraporController::class, 'peserta_didik_keluar'])->name('referensi.peserta-didik-keluar');
        Route::get('/password-peserta-didik', [EraporController::class, 'password_peserta_didik'])->name('referensi.password-peserta-didik');
        Route::get('/mata-pelajaran', [EraporController::class, 'mata_pelajaran'])->name('referensi.mata-pelajaran');
        Route::get('/ekstrakurikuler', [EraporController::class, 'ekstrakurikuler'])->name('referensi.ekstrakurikuler');
        Route::get('/teknik-penilaian', [EraporController::class, 'teknik_penilaian'])->name('referensi.teknik-penilaian');
        Route::get('/acuan-sikap', [EraporController::class, 'acuan_sikap'])->name('referensi.acuan-sikap');
        Route::get('/kompetensi-dasar', [EraporController::class, 'kompetensi_dasar'])->name('referensi.kompetensi-dasar');
        Route::get('/kompetensi-dasar/tambah', [EraporController::class, 'tambah_kompetensi_dasar'])->name('referensi.kompetensi-dasar.tambah');
        Route::get('/capaian-pembelajaran', [EraporController::class, 'capaian_pembelajaran'])->name('referensi.capaian-pembelajaran');
        Route::get('/capaian-pembelajaran/tambah', [EraporController::class, 'tambah_capaian_pembelajaran'])->name('referensi.capaian-pembelajaran.tambah');
        Route::get('/tujuan-pembelajaran', [EraporController::class, 'tujuan_pembelajaran'])->name('referensi.tujuan-pembelajaran');
        Route::get('/tujuan-pembelajaran/tambah', [EraporController::class, 'tambah_tujuan_pembelajaran'])->name('referensi.tujuan-pembelajaran.tambah');
        Route::get('/uji-kompetensi-keahlian', [EraporController::class, 'uji_kompetensi_keahlian'])->name('referensi.uji-kompetensi-keahlian');
        Route::get('/dudi', [EraporController::class, 'dudi'])->name('referensi.dudi');
    });
    Route::group(['prefix' => 'perencanaan'], function(){
        //Route::get('/penilaian-pusat-keunggulan', [EraporController::class, 'perencanaan_pusat_keunggulan'])->name('perencanaan.penilaian-pusat-keunggulan');
        Route::get('/penilaian-kurikulum-merdeka', [EraporController::class, 'perencanaan_kurikulum_merdeka'])->name('perencanaan.penilaian-kurikulum-merdeka');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'perencanaan_projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('perencanaan.projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/rasio-nilai-akhir', [EraporController::class, 'rasio_nilai_akhir'])->name('perencanaan.rasio-nilai-akhir');
        Route::get('/penilaian-pengetahuan', [EraporController::class, 'perencanaan_pengetahuan'])->name('perencanaan.penilaian-pengetahuan');
        Route::get('/penilaian-keterampilan', [EraporController::class, 'perencanaan_keterampilan'])->name('perencanaan.penilaian-keterampilan');
        Route::get('/bobot-keterampilan', [EraporController::class, 'bobot_keterampilan'])->name('perencanaan.bobot-keterampilan');
        Route::get('/penilaian-ukk', [EraporController::class, 'perencanaan_ukk'])->name('perencanaan.penilaian-ukk');
    });
    Route::group(['prefix' => 'penilaian'], function(){
        //Route::get('/pusat-keunggulan', [EraporController::class, 'penilaian_pusat_keunggulan'])->name('penilaian.pusat-keunggulan');
        Route::get('/nilai-akhir', [EraporController::class, 'nilai_akhir'])->name('penilaian.nilai-akhir');
        Route::get('/kurikulum-merdeka', [EraporController::class, 'penilaian_kurikulum_merdeka'])->name('penilaian.kurikulum-merdeka');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'penilaian_projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('penilaian.projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/pengetahuan', [EraporController::class, 'penilaian_pengetahuan'])->name('penilaian.pengetahuan');
        Route::get('/keterampilan', [EraporController::class, 'penilaian_keterampilan'])->name('penilaian.keterampilan');
        Route::group(['prefix' => 'sikap'], function(){
            Route::get('/', [EraporController::class, 'penilaian_sikap'])->name('penilaian.sikap');
            Route::get('/tambah', [EraporController::class, 'tambah_penilaian_sikap'])->name('penilaian.tambah_sikap');
        });
        Route::get('/remedial', [EraporController::class, 'penilaian_remedial'])->name('penilaian.remedial');
        Route::get('/ukk', [EraporController::class, 'penilaian_ukk'])->name('penilaian.ukk');
        Route::get('/ekstrakurikuler', [EraporController::class, 'penilaian_ekstrakurikuler'])->name('penilaian.ekstrakurikuler');
        Route::get('/capaian-kompetensi', [EraporController::class, 'capaian_kompetensi'])->name('penilaian.capaian-kompetensi');
    });
    Route::group(['prefix' => 'laporan'], function(){
        Route::get('/nilai-us', [EraporController::class, 'nilai_us'])->name('laporan.nilai-us');
        Route::get('/nilai-un', [EraporController::class, 'nilai_un'])->name('laporan.nilai-un');
        Route::get('/kewirausahaan', [EraporController::class, 'kewirausahaan'])->name('laporan.kewirausahaan');
        Route::get('/catatan-akademik', [EraporController::class, 'catatan_akademik'])->name('laporan.catatan-akademik');
        Route::get('/nilai-karakter', [EraporController::class, 'nilai_karakter'])->name('laporan.nilai-karakter');
        Route::get('/ketidakhadiran', [EraporController::class, 'ketidakhadiran'])->name('laporan.ketidakhadiran');
        Route::get('/nilai-ekstrakurikuler', [EraporController::class, 'nilai_ekstrakurikuler'])->name('laporan.nilai-ekstrakurikuler');
        Route::get('/pkl', [EraporController::class, 'pkl'])->name('laporan.pkl');
        Route::get('/prestasi-pd', [EraporController::class, 'prestasi_pd'])->name('laporan.prestasi-pd');
        Route::get('/kenaikan-kelas', [EraporController::class, 'kenaikan_kelas'])->name('laporan.kenaikan-kelas');
        Route::get('/rapor-uts', [EraporController::class, 'rapor_uts'])->name('laporan.rapor-uts');
        Route::get('/rapor-semester', [EraporController::class, 'rapor_semester'])->name('laporan.rapor-semester');
        Route::get('/rapor-nilai-akhir', [EraporController::class, 'rapor_nilai_akhir'])->name('laporan.rapor-nilai-akhir');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('laporan.projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/leger', [EraporController::class, 'leger'])->name('laporan.leger');
    });
    Route::group(['prefix' => 'monitoring'], function(){
        Route::get('/rekap-nilai', [EraporController::class, 'rekap_nilai'])->name('monitoring.rekap-nilai');
        Route::get('/analisis-nilai', [EraporController::class, 'analisis_nilai'])->name('monitoring.analisis-nilai');
        Route::get('/analisis-remedial', [EraporController::class, 'analisis_remedial'])->name('monitoring.analisis-remedial');
        Route::get('/capaian-kompetensi', [EraporController::class, 'monitoring_capaian_kompetensi'])->name('monitoring.capaian-kompetensi');
        Route::get('/prestasi-individu', [EraporController::class, 'prestasi_individu'])->name('monitoring.prestasi-individu');
    });
    Route::group(['prefix' => 'cetak'], function(){
        Route::get('/contoh', [CetakController::class, 'generate_pdf'])->name('cetak.contoh');
        Route::get('/rapor-uts/{rombongan_belajar_id}', [CetakController::class, 'rapor_uts'])->name('cetak.rapor-uts');
        Route::get('/rapor-cover/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_cover'])->name('cetak.rapor-cover');
        Route::get('/rapor-semester/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_semester'])->name('cetak.rapor-semester');
        Route::get('/rapor-nilai-akhir/{anggota_rombel_id}', [CetakController::class, 'rapor_nilai_akhir'])->name('cetak.rapor-nilai-akhir');
        Route::get('/rapor-p5/{anggota_rombel_id}', [CetakController::class, 'rapor_p5'])->name('cetak.rapor-p5');
        Route::get('/rapor-pelengkap/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_pelengkap'])->name('cetak.rapor-pelengkap');
        Route::get('/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', [CetakController::class, 'sertifikat'])->name('cetak.sertifikat');
        //Route::get('/cetak/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', 'CetakController@sertifikat');
    });
    Route::prefix('unduh')->name('unduhan.')->group( function(){
        Route::get('/leger-kd/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_kd'])->name('unduh-leger-kd');
        Route::get('/leger-nilai-akhir/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_akhir'])->name('unduh-leger-nilai-akhir');
        Route::get('/leger-nilai-rapor/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_rapor'])->name('unduh-leger-nilai-rapor');
        Route::get('/leger-nilai-kurmer/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_kurmer'])->name('unduh-leger-nilai-kurmer');
        Route::get('/template-nilai-akhir/{pembelajaran_id?}', [UnduhanController::class, 'template_nilai_akhir'])->name('template-nilai-akhir');
        Route::get('/template-nilai-kd/{rencana_penilaian_id?}', [UnduhanController::class, 'template_nilai_kd'])->name('template-nilai-kd');
        Route::get('/template-nilai-tp/{rencana_penilaian_id?}', [UnduhanController::class, 'template_nilai_tp'])->name('template-nilai-tp');
        Route::get('/template-tp/{cp_id?}', [UnduhanController::class, 'template_tp'])->name('template-tp');
    });
    Route::prefix('wali-kelas')->name('wali-kelas.')->group( function(){
        Route::get('/rapor-nilai-akhir', [EraporController::class, 'rapor_nilai_akhir'])->name('laporan.rapor-nilai-akhir');
        Route::get('/prestasi-pd', [EraporController::class, 'prestasi_pd'])->name('wali-kelas.prestasi-pd');
        Route::get('/ketidakhadiran', [EraporController::class, 'ketidakhadiran'])->name('wali-kelas.ketidakhadiran');
        Route::get('/nilai-ekstrakurikuler', [EraporController::class, 'nilai_ekskul'])->name('wali-kelas.nilai-ekstrakurikuler');
        Route::get('/leger', [EraporController::class, 'leger_kurmer'])->name('laporan.leger-kurmer');
    });
    Route::get('/unduhan', [EraporController::class, 'unduhan'])->name('pusat-unduhan');
    Route::get('/changelog', [EraporController::class, 'changelog'])->name('changelog');
    Route::get('/check-update', [EraporController::class, 'check_update'])->name('check-update');
});
Route::get('/url-server', function(){
    return config('erapor.dapodik').'sync/registrasi';
});
Route::get('/pengaturan-umum', function(){
    dd(config());
});
/*
* Warning!!!
* Hanya diaktifkan ketika lupa password admin!!!
* Cara pakai:
* Akses di browser http://localhost.8154/reset-password/email@gmail.com/password
* Non aktifkan kembali setelah selesai!!!!
*/
/*
Route::get('/reset-password/{email}/{password}', function($email, $password){
    $user = User::where('email', $email)->update(['password' => bcrypt($password)]);
    if($user){
        echo 'Password untuk email '.$email.' telah diganti menjadi '.$password;
    } else {
        echo 'Email '.$email.' tidak ditemukan di database!';
    }
})->name('home');
*/