<?php

namespace App\Http\Livewire\Pengaturan;

use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Guru;
use App\Models\Setting;
use App\Models\Rombongan_belajar;
use App\Models\Rombel_empat_tahun;
use Carbon\Carbon;
use Config;
use File;

class Index extends Component
{
    use LivewireAlert, WithFileUploads;
    public $semester_id;
    public $tanggal_rapor;
    public $tanggal_rapor_uts;
    public $zona;
    public $kepala_sekolah;
    public $tanggal_rapor_placehorlder;
    public $tanggal_rapor_uts_placehorlder;
    public $data_semester;
    public $data_guru;
    public $data_rombel;
    public $rombel_4_tahun = [];
    public $photo;
    public $sekolah;
    public $cara_penilaian;
    public $max_karakter = '';
    protected $rules = [
        'kepala_sekolah' => 'required',
        'semester_id' => 'required',
        'zona' => 'required',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:1024',
    ];
    protected $messages = [
        'semester_id.required' => 'Periode Aktif tidak boleh kosong.',
        'zona.required' => 'Zona Waktu tidak boleh kosong.',
        'kepala_sekolah.required' => 'Kepala sekolah tidak boleh kosong.',
        'photo.mimes' => 'Logo sekolah harus berupa berkas gambar (jpg, jpeg, png)',
        'photo.max' => 'Logo sekolah maksimal 1Mb.',
    ];
    public function getListeners()
    {
        return [
            'hapus_logo',
        ];
    }
    public function mount(){
        $this->semester_id = Semester::where('periode_aktif', 1)->first()->semester_id;
        $tanggal_rapor = config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.tanggal_rapor');
        $tanggal_rapor_uts = config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.tanggal_rapor_uts');
        $cara_penilaian = Setting::where(function($query){
            $query->where('key', 'cara_penilaian');
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
        })->first();
        $this->cara_penilaian = ($cara_penilaian) ? $cara_penilaian->value : 'sederhana';
        //config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian');
        if($tanggal_rapor){
            $this->tanggal_rapor = $tanggal_rapor;
            $this->tanggal_rapor_placehorlder = Carbon::parse($tanggal_rapor)->translatedFormat('d F Y');
        } else {
            $this->tanggal_rapor_placehorlder = Carbon::now()->translatedFormat('d F Y');
        }
        if($tanggal_rapor_uts){
            $this->tanggal_rapor_uts = $tanggal_rapor_uts;
            $this->tanggal_rapor_uts_placehorlder = Carbon::parse($tanggal_rapor_uts)->translatedFormat('d F Y');
        } else {
            $this->tanggal_rapor_uts_placehorlder = Carbon::now()->translatedFormat('d F Y');
        }
        $this->zona = config('global.'.session('sekolah_id').'.zona');
        $sekolah = Sekolah::find(session('sekolah_id'));
        $this->sekolah = $sekolah;
        $this->kepala_sekolah = $sekolah->guru_id;
        $this->data_guru = Guru::where('sekolah_id', session('sekolah_id'))->select('guru_id', 'nama')->get();
        $this->data_rombel = Rombongan_belajar::where(function($query){
			$query->where('jenis_rombel', 1);
			$query->where('sekolah_id', session('sekolah_id'));
			$query->where('semester_id', session('semester_aktif'));
			$query->where('tingkat', 12);
		})->select('rombongan_belajar_id', 'nama')->get();
        $rombel_4_tahun = Rombel_empat_tahun::where('sekolah_id', session('sekolah_id'))->where('semester_id', session('semester_aktif'))->select('rombongan_belajar_id')->get();
        $plucked = $rombel_4_tahun->pluck('rombongan_belajar_id');
        $this->rombel_4_tahun = $plucked->all();
        $this->max_karakter = (config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.max_karakter') ?? 100);
    }
    public function render()
    {
        $this->data_semester = Semester::whereHas('tahun_ajaran', function($query){
            $query->where('periode_aktif', 1);
        })->orderBy('semester_id', 'DESC')->get();
        return view('livewire.pengaturan.index', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Pengaturan'], ['name' => "Pengaturan Umum"]
            ]
        ]);
    }
    public function updatedPhoto()
    {
        $this->validate();
    }
    public function updatedSemesterId($value){
        $this->tanggal_rapor = config('global.'.session('sekolah_id').'.'.$value.'.tanggal_rapor') ?? Carbon::now()->format('Y-m-d');
        $this->tanggal_rapor_uts = config('global.'.session('sekolah_id').'.'.$value.'.tanggal_rapor_uts') ?? Carbon::now()->format('Y-m-d');
    }
    public function store(){
        $this->validate();
        if($this->semester_id){
            Semester::where('periode_aktif', 1)->update(['periode_aktif' => 0]);
            $s = Semester::find($this->semester_id);
            $s->periode_aktif = 1;
            $s->save();
        }
        Setting::whereIn('key', ['zona', 'tanggal_rapor', 'status_penilaian', 'last_sync'])->whereNull('sekolah_id')->delete();
        if($this->tanggal_rapor){
            Setting::updateOrCreate(
                [
                    'key' => 'tanggal_rapor',
                    'sekolah_id' => session('sekolah_id'),
                    'semester_id' => session('semester_aktif'),
                ],
                [
                    'value' => $this->tanggal_rapor,
                ]
            );
        }
        if($this->tanggal_rapor_uts){
            Setting::updateOrCreate(
                [
                    'key' => 'tanggal_rapor_uts',
                    'sekolah_id' => session('sekolah_id'),
                    'semester_id' => session('semester_aktif'),
                ],
                [
                    'value' => $this->tanggal_rapor_uts,
                ]
            );
        }
        Setting::updateOrCreate(
            [
                'key' => 'max_karakter',
                'sekolah_id' => session('sekolah_id'),
                'semester_id' => session('semester_aktif'),
            ],
            [
                'value' => $this->max_karakter,
            ]
        );
        Setting::updateOrCreate(
            [
                'key' => 'zona',
                'sekolah_id' => session('sekolah_id'),
            ],
            [
                'value' => $this->zona,
            ]
        );
        if($this->cara_penilaian){
            Setting::updateOrCreate(
                [
                    'key' => 'cara_penilaian',
                    'sekolah_id' => session('sekolah_id'),
                    'semester_id' => session('semester_aktif'),
                ],
                [
                    'value' => $this->cara_penilaian,
                ]
            );
            Config::set('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian', 'sederhana');
        }
        foreach($this->rombel_4_tahun as $rombel_4_tahun){
            Rombel_empat_tahun::updateOrCreate(
                [
                    'rombongan_belajar_id' => $rombel_4_tahun,
                    'sekolah_id' => session('sekolah_id'),
                    'semester_id' => session('semester_aktif')
                ],
                [
                    'last_sync' => now(),
                ]
            );
        }
        Rombel_empat_tahun::whereNotIn('rombongan_belajar_id', $this->rombel_4_tahun)->where('sekolah_id', session('sekolah_id'))->where('semester_id', session('semester_aktif'))->delete();
        $this->alert('success', 'Pengaturan berhasil disimpan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
        if($this->photo){
            if (!File::isDirectory(storage_path('app/public/images'))) {
                //MAKA FOLDER TERSEBUT AKAN DIBUAT
                File::makeDirectory(storage_path('app/public/images'));
            }
            $logo_sekolah = $this->photo->store('public/images');
            $this->sekolah->logo_sekolah = basename($logo_sekolah);
        }
        $this->sekolah->guru_id = $this->kepala_sekolah;
        $this->sekolah->save();
    }
    public function resetLogo(){
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Logo akan dikembalikan ke bawaan aplikasi',
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'hapus_logo',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function hapus_logo(){
        $this->sekolah->logo_sekolah = NULL;
        $this->sekolah->save();
        $this->alert('success', 'Logo berhasil direset', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
}
