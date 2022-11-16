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
                    <ol class="ps-1">
                        @if($os == 'WIN')
                        <li>Buka Command Prompt</li>
                        <li>Ketik <code>cd C:\eRaporSMK\dataweb</code> kemudian tekan <code>Enter</code></li>
                        <li>Ketik <code>php artisan erapor:update</code> dan tunggu sampai proses update aplikasi selesai</li>
                        <li>Pastikan di akhir informasi di Command Prompt, versi aplikasi sudah berubah</li>
                        @else
                        <li>Buka aplikasi Putty, jika belum ada, silahkan unduh <a href="https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html" target="_blank">disini</a> kemudian install</li>
                        <li>Login ke server</li>
                        <li>Masuk ke direktori dimana aplikasi e-Rapor SMK di install. Contoh <code>cd /var/www/html/eraporsmk</code> kemudian tekan <code>Enter</code></li>
                        <li>Ketik <code>php artisan erapor:update</code> dan tunggu sampai proses update aplikasi selesai</li>
                        <li>Pastikan di akhir informasi di Command Prompt, versi aplikasi sudah berubah</li>
                        @endif
                    </ol>
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
