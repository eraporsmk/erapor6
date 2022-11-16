<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_rapor extends Model
{
    use HasFactory;
    use Uuid;
	//use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    public $incrementing = false;
	protected $table = 'nilai_rapor';
	protected $primaryKey = 'nilai_rapor_id';
	protected $guarded = [];
	public function pembelajaran(){
		return $this->hasOne(Pembelajaran::class, 'pembelajaran_id', 'pembelajaran_id');
	}
}
