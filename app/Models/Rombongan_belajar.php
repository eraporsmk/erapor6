<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombongan_belajar extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'rombongan_belajar';
	protected $primaryKey = 'rombongan_belajar_id';
	protected $guarded = [];
	
	public function wali_kelas()
	{
		return $this->hasOne(Guru::class, 'guru_id', 'guru_id');
	}
	
	public function jurusan_sp()
	{
		return $this->hasOne(Jurusan_sp::class, 'jurusan_sp_id', 'jurusan_sp_id');
	}

	public function kurikulum()
	{
		return $this->hasOne(Kurikulum::class, 'kurikulum_id', 'kurikulum_id');
	}
	public function pembelajaran()
	{
		return $this->hasMany(Pembelajaran::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	
	public function anggota_rombel()
	{
		return $this->hasMany(Anggota_rombel::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function kelas_ekskul(){
		return $this->hasOne(Ekstrakurikuler::class, 'rombongan_belajar_id', 'rombongan_belajar_id');
	}
	public function semester()
	{
		return $this->hasOne(Semester::class, 'semester_id', 'semester_id');
	}
	public function jurusan()
	{
		return $this->hasOne(Jurusan::class, 'jurusan_id', 'jurusan_id');
	}
	public function sekolah()
	{
		return $this->hasOne(Sekolah::class, 'sekolah_id', 'sekolah_id');
	}
}
