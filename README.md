<p align="center"><img src="http://app.erapor-smk.net/logo.png" width="600"></p>

## Server Requirements
PHP >= 7.4.30

PHP cURL Extension

BCMath PHP Extension

Ctype PHP Extension

Fileinfo PHP extension

JSON PHP Extension

Mbstring PHP Extension

OpenSSL PHP Extension

PDO PHP Extension

Tokenizer PHP Extension

XML PHP Extension

PHP's xmlreader

PHP's xmlwriter

 PHP's zip

## Cara Install (Untuk Pengguna Baru)

- Clone Repositori ini
```bash
git clone --depth=1 https://github.com/eraporsmk/erapor6.git dataweb
cd dataweb
```

## Membuat file .env
```bash
cp .env.example .env
nano .env
```


- Install Dependencies
```bash
composer install
```

- Koneksi Database
```bash
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_name
DB_USERNAME=db_user
DB_PASSWORD=db_pass
```

## Generate App Key
```bash
php artisan key:generate
```

## Migration
- Membuat struktur table
```bash
php artisan migrate
```

- Jalankan seeder
```bash
php artisan db:seed
```
## Untuk pengguna windows:
- Panduan aplikasi & installer. silahkan download [disini](https://drive.google.com/drive/folders/1HXH77joT9J10eXcgxcTVYY9wfFmrfeVQ)

## Cara Install (Untuk Pengguna Lama)

- Clone Repositori ini
```bash
git clone --depth=1 https://github.com/eraporsmk/erapor6.git dataweb
cd dataweb
```

## Copy file .env
Copy file .env dari root folder aplikasi versi 5xx ke root folder aplikasi versi 6xx

- Install Dependencies
```bash
composer install
```

## Update Versi Aplikasi
```bash
php artisan erapor:update
```

## Edit file .env untuk menampilkan foto profile
```APP_URL=http://localhost:8154```

Sesuaikan dengan alamat/domain yang dipakai

Kemudian tambah kode dibawah ini agar laman register tidak tersedia

```REGISTRATION=false```

## Catatan khusus pengguna windows:
- Konfigurasi koneksi database seperti dibawah ini
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1

DB_PORT=58154
DB_DATABASE=windows
DB_USERNAME=windows
DB_PASSWORD=windows
```

