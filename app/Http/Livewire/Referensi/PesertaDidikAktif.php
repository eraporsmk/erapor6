<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Jurusan_sp;
use App\Models\Pekerjaan;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class PesertaDidikAktif extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    
    private function loggedUser(){
        return auth()->user();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function loadPerPage(){
        $this->resetPage();
    }
    protected $listeners = [
        'proses_sinkron',
        'show_progress'
    ];
    public $sortby = 'nama';
    public $sortbydesc = 'ASC';
    public $per_page = 10;
    public $rombongan_belajar_id;
    public $data = 'Peserta Didik';
    public $pd_id,
        $pd,
        $nama,
        $nis, 
        $nisn,
        $nik,
        $jenis_kelamin,
        $tempat_tanggal_lahir,
        $agama,
        $status,
        $anak_ke,
        $alamat,
        $rt_rw,
        $desa_kelurahan,
        $kecamatan,
        $kode_pos,
        $no_hp,
        $sekolah_asal,
        $diterima_kelas,
        $diterima,
        $email,
        $nama_ayah,
        $kerja_ayah,
        $nama_ibu,
        $kerja_ibu,
        $nama_wali,
        $alamat_wali,
        $telp_wali,
        $kerja_wali;
    public $filter_tingkat;
    public $filter_jurusan;
    public $filter_rombel;
    public $result = [];
    
    public function mount(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            $this->rombongan_belajar_id = $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id;
        } else {
            $this->rombongan_belajar_id = NULL;
        }
    }
    public function render(){
        return view('livewire.referensi.peserta-didik-aktif', [
            'collection' => Peserta_didik::whereHas('anggota_rombel', $this->kondisi())
            ->with(['anggota_rombel' => $this->kondisi()])
            ->orderBy($this->sortby, $this->sortbydesc)
            ->when($this->search, function($query) {
                $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhereHas('agama', function($query){
                    $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                });
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhere('tempat_lahir', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
            })->when($this->filter_tingkat, function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->wherehas('rombongan_belajar', function($query){
                        $query->where('tingkat', $this->filter_tingkat);
                    });
                });
            })->when($this->filter_jurusan, function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->wherehas('rombongan_belajar', function($query){
                        $query->where('jurusan_sp_id', $this->filter_jurusan);
                    });
                });
            })->when($this->filter_rombel, function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', $this->filter_rombel);
                });
            })->paginate($this->per_page),
            'pekerjaan_wali' => Pekerjaan::get(),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Peserta Didik Aktif"]
            ],
            /*'tombol_add' => ($this->rombongan_belajar_id) ? [
                'wire' => 'sinkronisasi',
                'link' => '',
                'color' => 'warning',
                'text' => 'Sinkronisasi'
            ] : NULL*/
        ]);
    }
    public function kondisi(){
        return function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                });
            }
            $query->with(['rombongan_belajar']);
        };
    }
    public function getID($pd_id){
        $this->pd_id = $pd_id;
        $this->pd = Peserta_didik::find($this->pd_id);
        $this->nama = $this->pd->nama;
        $this->nis = $this->pd->no_induk; 
        $this->nisn = $this->pd->nisn;
        $this->nik = $this->pd->nik;
        $this->jenis_kelamin = $this->pd->jenis_kelamin;
        $this->tempat_tanggal_lahir = $this->pd->tempat_lahir.', '.$this->pd->tanggal_lahir;
        $this->agama = ($this->pd->agama) ? $this->pd->agama->nama : '-';
        $this->status = $this->pd->status;
        $this->anak_ke = $this->pd->anak_ke;
        $this->alamat = $this->pd->alamat;
        $this->rt_rw = $this->pd->rt.'/'.$this->pd->rw;
        $this->desa_kelurahan = $this->pd->desa_kelurahan;
        $this->kecamatan = $this->pd->kecamatan;
        $this->kode_pos = $this->pd->kode_pos;
        $this->no_hp = $this->pd->no_hp;
        $this->sekolah_asal = $this->pd->sekolah_asal;
        $this->diterima_kelas = $this->pd->diterima_kelas;
        $this->diterima = $this->pd->diterima;
        $this->email = ($this->pd->user) ? $this->pd->user->email : $this->pd->email;
        $this->nama_ayah = $this->pd->nama_ayah;
        $this->kerja_ayah = $this->pd->kerja_ayah;
        $this->nama_ibu = $this->pd->nama_ibu;
        $this->kerja_ibu = $this->pd->kerja_ibu;
        $this->nama_wali = $this->pd->nama_wali;
        $this->alamat_wali = $this->pd->alamat_wali;
        $this->telp_wali = $this->pd->telp_wali;
        $this->kerja_wali = $this->pd->kerja_wali;
        $this->emit('show-modal');
    }
    public function updatedEmail(){
        $validation = ($this->pd->user) ? ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->pd->user->user_id, 'user_id')] : ['required', 'email', 'max:255', Rule::unique('users')];
        $this->validate(
            [
                'email' => $validation,
            ],
            [
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar di Database!',
            ]
        );
    }
    public function perbaharui(){
        $validation = ($this->pd->user) ? ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->pd->user->user_id, 'user_id')] : ['required', 'email', 'max:255', Rule::unique('users')];
        $this->validate(
            [
                'email' => $validation,
            ],
            [
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar di Database!',
            ]
        );
        $this->pd->status = $this->status;
        $this->pd->anak_ke = $this->anak_ke;
        $this->pd->diterima_kelas = $this->diterima_kelas;
        $this->pd->email = $this->email;
        $this->pd->nama_wali = $this->nama_wali;
        $this->pd->alamat_wali = $this->alamat_wali;
        $this->pd->telp_wali = $this->telp_wali;
        $this->pd->kerja_wali = $this->kerja_wali;
        if($this->pd->save()){
            if($this->pd->user){
                $this->pd->user->email = $this->email;
                $this->pd->user->save();
            } else {
                $role = Role::where('name', 'siswa')->first();
                $new_password = strtolower(Str::random(8));
                $user = User::create([
                    'name' => $this->pd->nama,
                    'email' => $this->email,
                    'nisn'	=> $this->nisn,
                    'password' => bcrypt($new_password),
                    'last_sync'	=> now(),
                    'sekolah_id'	=> session('sekolah_id'),
                    'password_dapo'	=> md5($new_password),
                    'peserta_didik_id'	=> $this->pd_id,
                    'default_password' => $new_password,
                ]);
                if(!$user->hasRole($role, session('semester_id'))){
                    $user->attachRole($role, session('semester_id'));
                }
            }
            $this->alert('success', 'Berhasil', [
                'html' => 'Data Peserta Didik berhasil diperbaharui!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'pembelajaranTersimpan',
                'allowOutsideClick' => false,
                'toast' => false,
            ]);
        } else {
            $this->alert('error', 'Pembelajaran gagal di reset. Coba beberapa saat lagi!', [
                'html' => 'Data Peserta Didik gagal diperbaharui.<br>Silahkan coba beberapa saat lagi!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
                'allowOutsideClick' => false,
                'toast' => false,
            ]);
        }
        $this->emit('close-modal');
    }
    public function tutup(){
        $this->reset(['pd_id', 'pd', 'nama', 'nis', 'nisn', 'nik', 'jenis_kelamin', 'tempat_tanggal_lahir', 'agama', 'status', 'anak_ke', 'alamat', 'rt_rw', 'desa_kelurahan', 'kecamatan', 'kode_pos', 'no_hp', 'sekolah_asal', 'diterima_kelas', 'diterima', 'email', 'nama_ayah', 'kerja_ayah', 'nama_ibu', 'kerja_ibu', 'nama_wali', 'alamat_wali', 'telp_wali', 'kerja_wali']);
    }
    public function syncPD($pd_id = NULL, $nama = NULL){
        $data_sync = [
            'peserta_didik_id' => ($pd_id) ? $pd_id : $this->pd_id,
            'sekolah_id'		=> session('sekolah_id'),
        ];
        $response = Http::withHeaders([
            'x-api-key' => session('sekolah_id'),
        ])->withBasicAuth('admin', '1234')->asForm()->post('http://app.erapor-smk.net/api/dapodik/diterima-dikelas', $data_sync);
        if($response->status() == 200){
            $data = $response->object();
            $diterima_kelas = '';
            if($pd_id){
                if($data->data){
                    Peserta_didik::where('peserta_didik_id', $pd_id)->whereNull('diterima_kelas')->update(['diterima_kelas' => $data->data->nama]);
                    $diterima_kelas = $data->data->nama;
                }
            } else {
                $this->diterima_kelas = ($data->data) ? $data->data->nama : '';
            }
            $this->result[$nama] = $diterima_kelas;
        }
    }
    private function url_server($server, $ep){
        return config('erapor.'.$server).$ep;
    }
    public function updatedFilterTingkat(){
        $this->reset(['filter_jurusan', 'filter_rombel']);
        if($this->filter_tingkat){
            $data_jurusan = Jurusan_sp::whereHas('rombongan_belajar', function($query){
                $query->where('tingkat', $this->filter_tingkat);
            })->where('sekolah_id', session('sekolah_id'))->get();
            $this->dispatchBrowserEvent('data_jurusan', ['data_jurusan' => $data_jurusan]);
        }
    }
    public function updatedFilterJurusan(){
        $this->reset(['filter_rombel']);
        if($this->filter_jurusan){
            $data_rombel = Rombongan_belajar::where('jurusan_sp_id', $this->filter_jurusan)->where('tingkat', $this->filter_tingkat)->get();
            $this->dispatchBrowserEvent('data_rombel', ['data_rombel' => $data_rombel]);
        }
    }
    public function updatedFilterRombel(){
        $this->rombongan_belajar_id = $this->filter_rombel;
    }
    public function sinkronisasi(){
        $this->reset(['result']);
        $data_siswa = Peserta_didik::whereHas('anggota_rombel', $this->kondisi())->select('peserta_didik_id', 'nama')->orderBy('nama')->get();
        foreach($data_siswa as $siswa){
            $this->syncPD($siswa->peserta_didik_id, $siswa->nama);
        }
        $this->emit('progress');
    }
}
