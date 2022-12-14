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
use App\Models\Kompetensi_dasar;
use App\Models\Tujuan_pembelajaran;
use Storage;

class TambahTp extends Component
{
    use WithFileUploads, LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $jenis_rombel;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $mata_pelajaran_id;
    public $kompetensi_id;
    public $cp_id;
    public $kd_id;
    public $merdeka;
    /*
    public $data_rombongan_belajar = [];
    public $data_pembelajaran = [];
    public $data_cp = [];
    public $data_kd = [];*/
    public $show_tp = FALSE;
    public $show_kd = FALSE;
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
    public function updatedTingkat(){
        $this->reset(['jenis_rombel', 'rombongan_belajar_id', 'mata_pelajaran_id', 'kompetensi_id', 'cp_id', 'kd_id', 'merdeka', 'show_tp', 'show_kd', 'show', 'template_excel']);
        $this->dispatchBrowserEvent('tingkat', ['tingkat' => 'tingkat']);
    }
    public function updatedJenisRombel(){
        $this->reset(['rombongan_belajar_id', 'mata_pelajaran_id', 'kompetensi_id', 'cp_id', 'kd_id', 'merdeka', 'show_tp', 'show_kd', 'show', 'template_excel']);
        if($this->jenis_rombel){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', $this->jenis_rombel);
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['mata_pelajaran_id', 'kompetensi_id', 'cp_id', 'kd_id', 'merdeka', 'show_tp', 'show_kd', 'show', 'template_excel']);
        if($this->rombongan_belajar_id){
            $rombel = Rombongan_belajar::find($this->rombongan_belajar_id);
            $this->merdeka = Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka');
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedMataPelajaranId(){
        $this->reset(['kompetensi_id', 'cp_id', 'kd_id', 'show_tp', 'show_kd', 'show', 'template_excel']);
        if($this->mata_pelajaran_id){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->whereNull('induk_pembelajaran_id');
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            if($this->merdeka){
                if($this->mata_pelajaran_id){
                    $fase = 'F';
                    if($this->tingkat == 10){
                        $fase = 'E';
                    }
                    $data_cp = Capaian_pembelajaran::where('mata_pelajaran_id', $this->mata_pelajaran_id)->where('fase', $fase)->where('aktif', 1)->orderBy('cp_id')->get();
                    $this->dispatchBrowserEvent('data_cp', ['data_cp' => $data_cp]);
                    $this->show_tp = TRUE;
                }
            } else {
                if($this->mata_pelajaran_id){
                    $this->show_kd = TRUE;
                }
            }
            $this->dispatchBrowserEvent('pharaonic.select2.init');
        }
    }
    public function updatedKompetensiId(){
        $this->reset(['cp_id', 'kd_id', 'show', 'template_excel']);
        if($this->kompetensi_id){
            $data_kd = Kompetensi_dasar::where(function($query){
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->where('kelas_'.$this->tingkat, 1);
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->where('aktif', 1);
            })->orderBy('id_kompetensi')->get();
            $this->dispatchBrowserEvent('data_kd', ['data_kd' => $data_kd]);
            $this->dispatchBrowserEvent('pharaonic.select2.init');
        }
    }
    public function updatedCpId(){
        $this->reset(['kd_id', 'show', 'template_excel']);
        if($this->cp_id){
            $this->show = TRUE;
        }
    }
    public function updatedKdId(){
        $this->reset(['cp_id', 'show', 'template_excel']);
        if($this->kd_id){
            $this->show = TRUE;
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereNull('induk_pembelajaran_id');
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNull('induk_pembelajaran_id');
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        };
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
        $id = ($this->cp_id) ?? $this->kd_id;
        Excel::import(new TemplateTp($this->mata_pelajaran_id, $id), storage_path('/app/public/'.$file_path));
        Storage::disk('public')->delete($file_path);
        $this->flash('success', 'Data Tujuan Pembelajaran (TP) berhasil disimpan!', [], '/referensi/tujuan-pembelajaran');
    }
    
    public function confirmed(){
        return redirect()->route('referensi.tujuan-pembelajaran');
    }
}
