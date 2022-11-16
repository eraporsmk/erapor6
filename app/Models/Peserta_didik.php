<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class Peserta_didik extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'peserta_didik';
	protected $primaryKey = 'peserta_didik_id';
	protected $guarded = [];
	
	public function anggota_rombel()
	{
		return $this->hasOne(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function anggota_ekskul()
	{
		return $this->hasOne(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function pd_keluar()
	{
		return $this->hasOne(Pd_keluar::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function all_anggota_rombel()
	{
		return $this->hasMany(Anggota_rombel::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function anggota_akt_pd()
	{
		return $this->hasOne(Anggota_akt_pd::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function getTanggalLahirAttribute()
	{
		return Carbon::parse($this->attributes['tanggal_lahir'])->translatedFormat('d F Y');
	}
	public function getDiterimaAttribute()
	{
		return ($this->attributes['diterima']) ? Carbon::parse($this->attributes['diterima'])->translatedFormat('d F Y') : '-';
	}
	public function getNamaAttribute()
	{
		return strtoupper($this->attributes['nama']);
	}
	public function getTempatLahirAttribute()
	{
		return strtoupper($this->attributes['tempat_lahir']);
	}
	public function agama()
	{
		return $this->belongsTo(Agama::class, 'agama_id', 'agama_id');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'peserta_didik_id', 'peserta_didik_id');
	}
	public function kelas(){
		return $this->hasOneThrough(
            Rombongan_belajar::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on users table...
            'rombongan_belajar_id', // Foreign key on history table...
            'peserta_didik_id', // Local key on suppliers table...
            'rombongan_belajar_id' // Local key on users table...
        );
	}
	public function pekerjaan_ayah(){
		return $this->hasOne(Pekerjaan::class, 'pekerjaan_id', 'kerja_ayah');
	}
	public function pekerjaan_ibu(){
		return $this->hasOne(Pekerjaan::class, 'pekerjaan_id', 'kerja_ibu');
	}
	public function pekerjaan_wali(){
		return $this->hasOne(Pekerjaan::class, 'pekerjaan_id', 'kerja_wali');
	}
	public function wilayah(){
		return $this->hasOne(Mst_wilayah::class, 'kode_wilayah', 'kode_wilayah')->with(['parrentRecursive']);
    }
	public function sekolah(){
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
	public function nilai_ekskul(){
		return $this->hasManyThrough(
            Nilai_ekstrakurikuler::class,
            Anggota_rombel::class,
            'peserta_didik_id', // Foreign key on history table...
            'anggota_rombel_id', // Foreign key on users table...
            'peserta_didik_id', // Local key on suppliers table...
            'anggota_rombel_id' // Local key on users table...
        );
	}
	public function nilai_ukk(){
		return $this->hasOne(Nilai_ukk::class, 'peserta_didik_id', 'peserta_didik_id');
	}
}
