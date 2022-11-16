<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Tujuan_pembelajaran;

class TemplateTp implements ToCollection//, WithStartRow
{
    public function __construct($mata_pelajaran_id, $cp_id) 
    {
        $this->mata_pelajaran_id = $mata_pelajaran_id;
        $this->cp_id = $cp_id;
    }
    /*public function startRow(): int
    {
        return 7;
    }*/
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        if(isset($collection[2][2]) && isset($collection[4][2])){
            $mata_pelajaran_id = $collection[2][2];
            $cp_id = $collection[4][2];
            if($mata_pelajaran_id == $this->mata_pelajaran_id && $cp_id == $this->cp_id){
                unset($collection[0], $collection[1], $collection[2], $collection[3], $collection[4], $collection[5], $collection[6]);
                foreach($collection as $tp){
                    if($tp[1]){
                        Tujuan_pembelajaran::create([
                            'cp_id' => $this->cp_id,
                            'deskripsi' => $tp[1],
                            'last_sync' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
