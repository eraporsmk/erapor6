<div>
    @include('panels.breadcrumb')
    <div class="content-body">    
        <div class="card">
            <div class="card-body">
                @if ($tersedia)
                    <div class="alert alert-success">
                        <div class="alert-body">
                            <p>Pembaharuan tersedia!</p>
                        </div>
                    </div>
                    <h4>Cara update aplikasi e-Rapor SMK</h4>
                    @if($os == 'WIN')
                    <ol class="ps-1" type="a">
                        <li><strong>Manual</strong>
                            <ul style="padding-left: 10px;">
                                <li>Buka Command Prompt (CMD) Run as Administrator</li>
                                <li>Ketik <code>cd C:\eRaporSMK\dataweb</code> [enter]</li>
                                <li>Ketik <code>git stash</code> [enter]</li>
                                <li>Ketik <code>git clean -df</code> [enter]</li>
                                <li>Ketik <code>git pull origin main</code> [enter]. Tunggu sampai proses update file dari github selesai</li>
                                <li>Ketik <code>composer update</code> [enter]</li>
                                <li>Ketik <code>php artisan erapor:update</code>. Tunggu sampai proses update versi aplikasi selesai.</li>
                                <li>Pastikan di akhir informasi di Command Prompt, versi aplikasi sudah berubah</li>
                                <li>Cek kembali aplikasi e-Rapor SMK, jika ada yang gagal silahkan laporkan ke Tim Helpdesk</li>
                            </ul>
                        </li>
                        <br>
                        <li><strong>Menggunakan file .bat</strong>
                            <ul style="padding-left: 10px;">
                                <li>Silahkan download file <strong>updater e-Rapor SMK V6.0.1.bat</strong> <a href="https://drive.google.com/file/d/1cBZWtlGqv_bgRFa3CJnVCXzpaGVlg1u1/view" target="_blank">disini</a>.</li>
                                <li>Buka file <strong>updater e-Rapor SMK V6.0.1.bat</strong> dengan cara klik kanan dan pilih Run as Administartor.</li>
                                <li>Tunggu sampai proses update versi aplikasi selesai.</li>
                            </ul>
                        </li>
                    </ol>
                    @else
                    <ul style="padding-left: 10px;">
                        <li>Buka aplikasi Putty, jika belum ada, silahkan unduh <a href="https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html" target="_blank">disini</a> kemudian install</li>
                        <li>Login ke SSH menggunakan username & password yang dimiliki</li>
                        <li>Masuk ke root direktori aplikasi e-Rapor SMK di install.(Contoh <code>cd /var/www/html/erapor</code> [enter])</li>
                        <li>Ketik <code>git stash</code> [enter]</li>
                        <li>Ketik <code>git clean -df</code> [enter]</li>
                        <li>Ketik <code>git pull origin main</code> [enter]. Tunggu sampai proses update file dari github selesai.</li>
                        <li>Ketik <code>composer update</code> [enter]</li>
                        <li>Ketik <code>php artisan erapor:update</code>. Tunggu sampai proses update versi aplikasi selesai.</li>
                        <li>Pastikan di akhir informasi di SSH, versi aplikasi sudah berubah</li>
                        <li>Cek kembali aplikasi e-Rapor SMK, jika ada yang gagal silahkan laporkan ke Tim Helpdesk</li>
                    </ul>
                    @endif
                @else
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            <p>Pembaharuan belum tersedia!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
