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
        Route::post('/register',[LoginController::class, 'process_signup'])->name('registrasi');
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
    Route::prefix('sinkronisasi')->name('sinkronisasi.')->middleware('team:admin')->group( function(){
        Route::get('/dapodik', [EraporController::class, 'dapodik'])->name('dapodik');
        Route::get('/erapor', [EraporController::class, 'erapor'])->name('erapor');
        Route::get('/nilai-dapodik', [EraporController::class, 'nilai_dapodik'])->name('nilai_dapodik');
    });
    // Route Components
    Route::prefix('setting')->name('setting.')->middleware('team:admin')->group( function(){
        Route::get('/umum', [EraporController::class, 'settings'])->name('index');
        Route::get('/users', [EraporController::class, 'users'])->name('users');
    });
    Route::prefix('referensi')->name('referensi.')->middleware('team:admin,guru,tu')->group( function(){
    //Route::group(['prefix' => 'referensi'], function(){
        Route::get('/guru', [EraporController::class, 'guru'])->name('guru');
        Route::get('/tendik', [EraporController::class, 'tendik'])->name('tendik');
        Route::get('/instruktur', [EraporController::class, 'instruktur'])->name('instruktur');
        Route::get('/asesor', [EraporController::class, 'asesor'])->name('asesor');
        Route::get('/rombongan-belajar', [EraporController::class, 'rombongan_belajar'])->name('rombongan-belajar')->middleware('team:admin,waka,tu');
        Route::get('/rombel-pilihan', [EraporController::class, 'rombel_pilihan'])->name('rombel-pilihan')->middleware('team:admin,waka,tu');
        Route::get('/peserta-didik-aktif', [EraporController::class, 'peserta_didik_aktif'])->name('peserta-didik-aktif');
        Route::get('/peserta-didik-keluar', [EraporController::class, 'peserta_didik_keluar'])->name('peserta-didik-keluar');
        Route::get('/password-peserta-didik', [EraporController::class, 'password_peserta_didik'])->name('password-peserta-didik')->middleware('team:wali');
        Route::get('/mata-pelajaran', [EraporController::class, 'mata_pelajaran'])->name('mata-pelajaran');
        Route::get('/ekstrakurikuler', [EraporController::class, 'ekstrakurikuler'])->name('ekstrakurikuler');
        Route::get('/teknik-penilaian', [EraporController::class, 'teknik_penilaian'])->name('teknik-penilaian')->middleware('team:admin');
        Route::get('/acuan-sikap', [EraporController::class, 'acuan_sikap'])->name('acuan-sikap')->middleware('team:admin');
        Route::get('/kompetensi-dasar', [EraporController::class, 'kompetensi_dasar'])->name('kompetensi-dasar')->middleware('team:guru');
        Route::get('/kompetensi-dasar/tambah', [EraporController::class, 'tambah_kompetensi_dasar'])->name('kompetensi-dasar.tambah');
        Route::get('/capaian-pembelajaran', [EraporController::class, 'capaian_pembelajaran'])->name('capaian-pembelajaran');
        Route::get('/capaian-pembelajaran/tambah', [EraporController::class, 'tambah_capaian_pembelajaran'])->name('capaian-pembelajaran.tambah');
        Route::get('/tujuan-pembelajaran', [EraporController::class, 'tujuan_pembelajaran'])->name('tujuan-pembelajaran');
        Route::get('/tujuan-pembelajaran/tambah', [EraporController::class, 'tambah_tujuan_pembelajaran'])->name('tujuan-pembelajaran.tambah');
        Route::get('/uji-kompetensi-keahlian', [EraporController::class, 'uji_kompetensi_keahlian'])->name('uji-kompetensi-keahlian')->middleware('team:kaprog');
        Route::get('/dudi', [EraporController::class, 'dudi'])->name('dudi')->middleware('team:admin');
    });
    Route::prefix('perencanaan')->name('perencanaan.')->middleware('team:guru')->group( function(){
        //Route::get('/penilaian-pusat-keunggulan', [EraporController::class, 'perencanaan_pusat_keunggulan'])->name('penilaian-pusat-keunggulan');
        Route::get('/penilaian-kurikulum-merdeka', [EraporController::class, 'perencanaan_kurikulum_merdeka'])->name('penilaian-kurikulum-merdeka');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'perencanaan_projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/rasio-nilai-akhir', [EraporController::class, 'rasio_nilai_akhir'])->name('rasio-nilai-akhir');
        Route::get('/penilaian-pengetahuan', [EraporController::class, 'perencanaan_pengetahuan'])->name('penilaian-pengetahuan');
        Route::get('/penilaian-keterampilan', [EraporController::class, 'perencanaan_keterampilan'])->name('penilaian-keterampilan');
        Route::get('/bobot-keterampilan', [EraporController::class, 'bobot_keterampilan'])->name('bobot-keterampilan');
        Route::get('/penilaian-ukk', [EraporController::class, 'perencanaan_ukk'])->name('penilaian-ukk');
    });
    Route::prefix('penilaian')->name('penilaian.')->middleware('team:guru,tu')->group( function(){
        //Route::get('/pusat-keunggulan', [EraporController::class, 'penilaian_pusat_keunggulan'])->name('pusat-keunggulan');
        Route::get('/nilai-akhir', [EraporController::class, 'nilai_akhir'])->name('nilai-akhir');
        Route::get('/kurikulum-merdeka', [EraporController::class, 'penilaian_kurikulum_merdeka'])->name('kurikulum-merdeka');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'penilaian_projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/pengetahuan', [EraporController::class, 'penilaian_pengetahuan'])->name('pengetahuan');
        Route::get('/keterampilan', [EraporController::class, 'penilaian_keterampilan'])->name('keterampilan');
        Route::group(['prefix' => 'sikap'], function(){
            Route::get('/', [EraporController::class, 'penilaian_sikap'])->name('sikap');
            Route::get('/tambah', [EraporController::class, 'tambah_penilaian_sikap'])->name('tambah_sikap');
        });
        Route::get('/remedial', [EraporController::class, 'penilaian_remedial'])->name('remedial');
        Route::get('/ukk', [EraporController::class, 'penilaian_ukk'])->name('ukk');
        Route::get('/ekstrakurikuler', [EraporController::class, 'penilaian_ekstrakurikuler'])->name('ekstrakurikuler');
        Route::get('/capaian-kompetensi', [EraporController::class, 'capaian_kompetensi'])->name('capaian-kompetensi');
    });
    Route::prefix('laporan')->name('laporan.')->middleware('team:guru')->group( function(){
    //Route::group(['prefix' => 'laporan', 'middleware' => ['role:guru']], function(){
        Route::get('/nilai-us', [EraporController::class, 'nilai_us'])->name('nilai-us');
        Route::get('/nilai-un', [EraporController::class, 'nilai_un'])->name('nilai-un');
        Route::get('/kewirausahaan', [EraporController::class, 'kewirausahaan'])->name('kewirausahaan');
        Route::get('/catatan-akademik', [EraporController::class, 'catatan_akademik'])->name('catatan-akademik');
        Route::get('/nilai-karakter', [EraporController::class, 'nilai_karakter'])->name('nilai-karakter');
        Route::get('/ketidakhadiran', [EraporController::class, 'ketidakhadiran'])->name('ketidakhadiran');
        Route::get('/nilai-ekstrakurikuler', [EraporController::class, 'nilai_ekstrakurikuler'])->name('nilai-ekstrakurikuler');
        Route::get('/prakerin', [EraporController::class, 'pkl'])->name('pkl');
        Route::get('/prestasi-pd', [EraporController::class, 'prestasi_pd'])->name('prestasi-pd');
        Route::get('/kenaikan-kelas', [EraporController::class, 'kenaikan_kelas'])->name('kenaikan-kelas');
        Route::get('/rapor-uts', [EraporController::class, 'rapor_uts'])->name('rapor-uts');
        Route::get('/rapor-semester', [EraporController::class, 'rapor_semester'])->name('rapor-semester');
        Route::get('/rapor-nilai-akhir', [EraporController::class, 'rapor_nilai_akhir'])->name('rapor-nilai-akhir');
        Route::get('/projek-profil-pelajar-pancasila-dan-budaya-kerja', [EraporController::class, 'projek_profil_pelajar_pancasila_dan_budaya_kerja'])->name('projek-profil-pelajar-pancasila-dan-budaya-kerja');
        Route::get('/leger', [EraporController::class, 'leger'])->name('leger');
    });
    Route::prefix('monitoring')->name('monitoring.')->middleware('team:guru')->group( function(){
    //Route::group(['prefix' => 'monitoring', 'middleware' => ['role:guru']], function(){
        Route::get('/rekap-nilai', [EraporController::class, 'rekap_nilai'])->name('rekap-nilai');
        Route::get('/analisis-nilai', [EraporController::class, 'analisis_nilai'])->name('analisis-nilai');
        Route::get('/analisis-remedial', [EraporController::class, 'analisis_remedial'])->name('analisis-remedial');
        Route::get('/capaian-kompetensi', [EraporController::class, 'monitoring_capaian_kompetensi'])->name('capaian-kompetensi');
        Route::get('/prestasi-individu', [EraporController::class, 'prestasi_individu'])->name('prestasi-individu');
    });
    Route::prefix('cetak')->name('cetak.')->middleware('team:guru,tu')->group( function(){
    //Route::group(['prefix' => 'cetak', 'middleware' => ['role:guru']], function(){
        Route::get('/contoh', [CetakController::class, 'generate_pdf'])->name('contoh');
        Route::get('/rapor-uts/{rombongan_belajar_id}', [CetakController::class, 'rapor_uts'])->name('rapor-uts');
        Route::get('/rapor-cover/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_cover'])->name('rapor-cover');
        Route::get('/rapor-semester/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_semester'])->name('rapor-semester');
        Route::get('/rapor-nilai-akhir/{anggota_rombel_id}', [CetakController::class, 'rapor_nilai_akhir'])->name('rapor-nilai-akhir');
        Route::get('/rapor-p5/{anggota_rombel_id}', [CetakController::class, 'rapor_p5'])->name('rapor-p5');
        Route::get('/rapor-pelengkap/{anggota_rombel_id}/{rombongan_belajar_id?}', [CetakController::class, 'rapor_pelengkap'])->name('rapor-pelengkap');
        Route::get('/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', [CetakController::class, 'sertifikat'])->name('sertifikat');
        //Route::get('/cetak/sertifikat/{anggota_rombel_id}/{rencana_ukk_id}', 'CetakController@sertifikat');
    });
    Route::prefix('unduh')->name('unduhan.')->middleware('team:guru')->group( function(){
        Route::get('/leger-kd/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_kd'])->name('unduh-leger-kd');
        Route::get('/leger-nilai-akhir/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_akhir'])->name('unduh-leger-nilai-akhir');
        Route::get('/leger-nilai-rapor/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_rapor'])->name('unduh-leger-nilai-rapor');
        Route::get('/leger-nilai-kurmer/{rombongan_belajar_id}', [UnduhanController::class, 'unduh_leger_nilai_kurmer'])->name('unduh-leger-nilai-kurmer');
        Route::get('/template-nilai-akhir/{pembelajaran_id?}', [UnduhanController::class, 'template_nilai_akhir'])->name('template-nilai-akhir');
        Route::get('/template-nilai-kd/{rencana_penilaian_id?}', [UnduhanController::class, 'template_nilai_kd'])->name('template-nilai-kd');
        Route::get('/template-nilai-tp/{rencana_penilaian_id?}', [UnduhanController::class, 'template_nilai_tp'])->name('template-nilai-tp');
        Route::get('/template-tp/{id?}/{rombongan_belajar_id?}/{pembelajaran_id?}', [UnduhanController::class, 'template_tp'])->name('template-tp');
    });
    Route::prefix('wali-kelas')->name('wali-kelas.')->middleware('team:wali,waka,tu')->group( function(){
    //Route::group(['prefix' => 'wali-kelas', 'middleware' => ['role:guru'], 'name' => 'wali-kelas.'], function(){
        Route::get('/praktik-kerja-lapangan', [EraporController::class, 'pkl'])->name('pkl');
        Route::get('/rapor-nilai-akhir', [EraporController::class, 'rapor_nilai_akhir'])->name('rapor-nilai-akhir');
        Route::get('/prestasi-pd', [EraporController::class, 'prestasi_pd'])->name('prestasi-pd');
        Route::get('/catatan-sikap', [EraporController::class, 'catatan_sikap'])->name('catatan-sikap');
        Route::get('/ketidakhadiran', [EraporController::class, 'ketidakhadiran'])->name('ketidakhadiran');
        Route::get('/kenaikan-kelas', [EraporController::class, 'kenaikan_kelas'])->name('kenaikan-kelas');
        Route::get('/nilai-ekstrakurikuler', [EraporController::class, 'nilai_ekstrakurikuler'])->name('nilai-ekstrakurikuler');
        Route::get('/leger', [EraporController::class, 'leger_kurmer'])->name('leger-kurmer');
    });
    Route::get('/unduhan', [EraporController::class, 'unduhan'])->name('pusat-unduhan');
    Route::get('/changelog', [EraporController::class, 'changelog'])->name('changelog')->middleware('team:admin');
    Route::get('/check-update', [EraporController::class, 'check_update'])->name('check-update')->middleware('team:admin');
});
Route::get('/url-server', function(){
    return config('erapor.dapodik').'sync/registrasi';
})->middleware('team:user');
Route::get('/pengaturan-umum', function(){
    dd(config());
})->middleware('team:user');
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