<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Imports\TemplateTp;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;
use Storage;

class TambahTp extends Component
{
    use WithFileUploads, LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $cp_id;
    public $data_rombongan_belajar = [];
    public $data_pembelajaran = [];
    public $data_cp = [];
    public $show = FALSE;
    public $template_excel;

    protected $listeners = [
        'confirmed'
    ];

    public function getListeners()
    {
        return [
            'changeTingkat',
            'changeRombel',
        ];
    }
    protected $rules = [
        'tingkat' => 'required',
        'rombongan_belajar_id' => 'required',
        'mata_pelajaran_id' => 'required',
        'cp_id' => 'required',
        'deskripsi' => 'required',
    ];

    protected $messages = [
        'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
        'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
        'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
        'cp_id.required' => 'Capaian Pembelajaran (CP) tidak boleh kosong!!',
        'deskripsi.required' => 'Deskripsi Tujuan Pembelajaran (TP) tidak boleh kosong!!',
    ];
    public function loggedUser(){
        return auth()->user();
    }
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.referensi.tambah-tp', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Tambah Tujuan Pembelajaran (TP)"]
            ]
        ]);
    }
    public function changeTingkat(){
        $this->reset(['data_rombongan_belajar', 'data_pembelajaran', 'data_cp', 'show']);
        if($this->tingkat){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                //$query->where('jenis_rombel', 1);
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
        };
    }
    public function changeRombel(){
        $this->reset(['data_pembelajaran', 'data_cp', 'show']);
        if($this->rombongan_belajar_id){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
        }
    }
    public function changeMapel(){
        $this->reset(['data_cp', 'show']);
        if($this->mata_pelajaran_id){
            $fase = 'F';
            if($this->tingkat == 10){
                $fase = 'E';
            }
            $this->data_cp = Capaian_pembelajaran::where('mata_pelajaran_id', $this->mata_pelajaran_id)->where('fase', $fase)->orderBy('cp_id')->get();
        }
    }
    public function changeCp(){
        if($this->cp_id){
            $this->show = TRUE;
        }
    }
    public function updatedTemplateExcel()
    {
        $this->validate(
            [
                'template_excel' => 'mimes:xlsx', // 1MB Max
            ],
            [
                'template_excel.mimes' => 'File harus berupa file dengan ekstensi: xlsx.',
            ]
        );
        $file_path = $this->template_excel->store('files', 'public');
        //return (new LeggerKDExport)->query(request()->route('rombongan_belajar_id'))->download($nama_file);
        Excel::import(new TemplateTp($this->mata_pelajaran_id, $this->cp_id), storage_path('/app/public/'.$file_path));
        /*$imported_data = (new FastExcel)->import(storage_path('/app/public/'.$file_path));
        $collection = collect($imported_data);
        dd($collection);
        foreach($collection as $nilai){
            dd($nilai);
            //Tujuan_pembelajaran::create();
        }*/
        Storage::disk('public')->delete($file_path);
        $this->flash('success', 'Data Tujuan Pembelajaran (TP) berhasil disimpan!', [], '/referensi/tujuan-pembelajaran');
        /*$this->alert('success', 'Data Tujuan Pembelajaran (TP) berhasil disimpan!', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);*/
    }
    
    public function confirmed(){
        return redirect()->route('referensi.tujuan-pembelajaran');
    }
}
