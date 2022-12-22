<?php

namespace App\Http\Livewire\Pengaturan;

use Livewire\WithPagination;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Guru;
use App\Models\Peserta_didik;
use App\Models\Role;
use App\Models\Rombongan_belajar;
use App\Models\Ekstrakurikuler;
use App\Models\Pembelajaran;

class Users extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['generatePengguna', 'generatePtk', 'generatePd', 'confirmed', 'confirmReset'];
    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function loadPerPage(){
        $this->resetPage();
    }
    public function filterAkses(){
        $this->resetPage();
    }
    public $sortby = 'last_login_at';
    public $sortbydesc = 'DESC';
    public $per_page = 10;
    public $role_id = '';
    public $user_id;
    public $pengguna;
    public $roles = [];
    public $akses = [];
    public $is_guru;
    public function render()
    {
        $loggedUser = auth()->user();
        $where = function($query){
            $query->whereRoleIs(['guru', 'siswa', 'tu'], session('semester_id'));
            $query->where('sekolah_id', session('sekolah_id'));
        };
        return view('livewire.pengaturan.users', [
            'data_user' => User::where($where)->orderBy($this->sortby, $this->sortbydesc)
            ->when($this->search, function($query) use ($where){
                $query->where($where);
                $query->where('name', 'ILIKE', '%' . $this->search . '%');
                $query->orWhere('nuptk', 'ILIKE', '%' . $this->search . '%');
                $query->where($where);
                $query->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
                $query->where($where);
                $query->orWhere('email', 'ILIKE', '%' . $this->search . '%');
                $query->where($where);
            })->when($this->role_id, function($ptk) {
                if($this->role_id !== 'all'){
                    $ptk->whereRoleIs($this->role_id, session('semester_id'));
                }
            })->paginate($this->per_page),
            'hak_akses' => Role::whereNotIn('id', [1,2,6])->get(),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Pengaturan'], ['name' => "Hak Akses Pengguna"]
            ],
            'tombol_add' => 
            [
                'wire' => 'generatePengguna',
                'link' => '',
                'color' => 'success',
                'text' => 'Atur Ulang Pengguna'
            ]
        ]);
    }
    private function check_email($user, $field){
        $loggedUser = auth()->user();
        $random = Str::random(8);
		$user->email = ($user->email != $loggedUser->email) ? $user->email : strtolower($random).'@erapor-smk.net';
		$user->email = strtolower($user->email);
        if($field == 'guru_id'){
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->ptk_id)->first();
		} else {
            $find_user_email = User::where('email', $user->email)->where($field, '<>', $user->peserta_didik_id)->first();
		}
        $find_user_email = User::where('email', $user->email)->first();
		if($find_user_email){
			$user->email = strtolower($random).'@erapor-smk.net';
		}
        return $user->email;
    }
    public function generatePtk(){
        $data = Guru::where(function($query){
            if(Schema::hasTable('ptk_keluar')){
                $query->whereDoesntHave('ptk_keluar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                });
            }
            $query->where('sekolah_id', session('sekolah_id'));
            $query->whereNotNull('email');
        })->get();
        $jenis_tu = jenis_gtk('tendik');
		$asesor = jenis_gtk('asesor');
        $PembinaRole = Role::where('name', 'pembina_ekskul')->first();
        $p5Role = Role::where('name', 'guru-p5')->first();
        $WalasRole = Role::where('name', 'wali')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $new = 0;
        if($data){
            foreach($data as $d){
                $new++;
                $new_password = strtolower(Str::random(8));
                $user = User::where('guru_id', $d->guru_id)->first();
                if(!$user){
                    $user_email = $this->check_email($d, 'guru_id');
                    $user = User::create([
                        'name' => $d->nama_lengkap,
						'email' => $user_email,
						'nuptk'	=> $d->nuptk,
						'password' => bcrypt($new_password),
						'last_sync'	=> now(),
						'sekolah_id'	=> session('sekolah_id'),
						'password_dapo'	=> md5($new_password),
						'guru_id'	=> $d->guru_id,
						'default_password' => $new_password,
                    ]);
                } else {
                    $user->name = $d->nama_lengkap;
                    $user->save();
                }
                $user->detachRole($adminRole, session('semester_id'));
                if($jenis_tu->contains($d->jenis_ptk_id)){
                    $role = Role::where('name', 'tu')->first();
                } elseif($asesor->contains($d->jenis_ptk_id)){
                    $role = Role::where('name', 'user')->first();
                } else {
                    $role = Role::where('name', 'guru')->first();
                }
                if(!$user->hasRole($role, session('semester_id'))){
                    $user->attachRole($role, session('semester_id'));
                }
                $find_rombel = Rombongan_belajar::where('guru_id', $d->guru_id)->where('semester_id', session('semester_aktif'))->where('jenis_rombel', 1)->first();
				if($find_rombel){
                    if(!$user->hasRole($WalasRole, session('semester_id'))){
                        $user->attachRole($WalasRole, session('semester_id'));
                    }
                } else {
                    if($user->hasRole($WalasRole, session('semester_id'))){
                        $user->detachRole($WalasRole, session('semester_id'));
                    }
                }
                $find_mapel_p5 = Pembelajaran::where('guru_id', $d->guru_id)->where('semester_id', session('semester_aktif'))->where('mata_pelajaran_id', '200040000')->has('tema')->first();
                if($find_mapel_p5){
                    if(!$user->hasRole($p5Role, session('semester_id'))){
                        $user->attachRole($p5Role, session('semester_id'));
                    }
                } else {
                    if($user->hasRole($p5Role, session('semester_id'))){
                        $user->detachRole($p5Role, session('semester_id'));
                    }
                }
                $find_ekskul = Ekstrakurikuler::where('guru_id', $d->guru_id)->where('semester_id', session('semester_aktif'))->first();
                if($find_ekskul){
                    if(!$user->hasRole($PembinaRole, session('semester_id'))){
                        $user->attachRole($PembinaRole, session('semester_id'));
                    }
                } else {
                    if($user->hasRole($PembinaRole, session('semester_id'))){
                        $user->detachRole($PembinaRole, session('semester_id'));
                    }
                }
            }
        }
        if($new){
            $this->alert('success', 'Berhasil', [
                'html' => 'Pengguna PTK berhasil diperbaharui',
                'showCancelButton' => true,
                'cancelButtonText' => 'OK',
                'timer' => null
            ]);
        } else {
            $this->alert('success', 'error', [
                'html' => 'Pengguna PTK gagal diperbaharui. Silahkan coba beberapa saat lagi!',
                'showCancelButton' => true,
                'cancelButtonText' => 'OK',
                'timer' => null
            ]);
        }
    }
    public function generatePd(){
        $data = Peserta_didik::where(function($query){
            $query->whereDoesntHave('pd_keluar', function($query){
                $query->where('semester_id', session('semester_aktif'));
            });
            $query->where('sekolah_id', session('sekolah_id'));
        })->get();
        $role = Role::where('name', 'siswa')->first();
        $adminRole = Role::where('name', 'admin')->first();
        if($data){
            foreach($data as $d){
                $new_password = strtolower(Str::random(8));
                $user = User::where('peserta_didik_id', $d->peserta_didik_id)->first();
                if(!$user){
                    $user_email = $this->check_email($d, 'peserta_didik_id');
                    $user = User::create([
                        'name' => $d->nama,
						'email' => $user_email,
						'nisn'	=> $d->nisn,
						'password' => bcrypt($new_password),
						'last_sync'	=> now(),
						'sekolah_id'	=> session('sekolah_id'),
						'password_dapo'	=> md5($new_password),
						'peserta_didik_id'	=> $d->peserta_didik_id,
						'default_password' => $new_password,
                    ]);
                }
                $user->detachRole($adminRole, session('semester_id'));
                if(!$user->hasRole($role, session('semester_id'))){
                    $user->attachRole($role, session('semester_id'));
                }
            }
        }
        $this->alert('success', 'Berhasil', [
            'text' => 'Pengguna PD berhasil diperbaharui',
            'showCancelButton' => true,
            'cancelButtonText' => 'OK',
            'timer' => null
        ]);
    }
    public function generatePengguna(){
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Akun PTK',
            'showLoaderOnConfirm' => true,
            'onConfirmed' => 'generatePtk',
            'showDenyButton' => true,
            'denyButtonText' => 'Akun PD',
            'showLoaderOnDeny' => true,
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'onDenied' => 'generatePd',
            'onDismissed' => 'cancelled',
            'allowOutsideClick' => false,//'() => !Swal.isLoading()',
            'timer' => null
        ]);
    }
    public function view($user_id){
        $this->reset(['akses']);
        $this->pengguna = User::find($user_id);
        if($this->pengguna->guru_id){
            $this->roles = Role::find([7,8,9]);   
        }
        $this->emit('openView');
    }
    public function hapusAkses($user_id, $role){
        $this->pengguna->detachRole($role, session('semester_id'));
        $this->alert('success', 'Berhasil', [
            'text' => 'Hak Akses berhasil dihapus'
        ]);
        $this->pengguna = User::find($user_id);
    }
    public function destroy($user_id){
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'allowOutsideClick' => false,//'() => !Swal.isLoading()',
            'timer' => null
        ]);
    }
    public function update(){
        foreach($this->akses as $akses){
            if(!$this->pengguna->hasRole($akses, session('semester_id'))){
                $this->pengguna->attachRole($akses, session('semester_id'));
            }
        }
        $this->emit('close-modal');
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Pengguna berhasil diperbaharui'
        ]);
        $this->resetPage();
    }
    public function confirmed(){
        $this->alert('success', 'Berhasil', [
            'text' => 'Pengguna berhasil dihapus'
        ]);
        $this->resetPage();
    }
    public function resetPassword($user_id){
        $this->user_id = $user_id;
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin',
            'onConfirmed' => 'confirmReset',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'allowOutsideClick' => false,//'() => !Swal.isLoading()',
            'timer' => null
        ]);
    }
    public function confirmReset(){
        $user = User::find($this->user_id);
        if(!$user->default_password){
            $user->default_password = strtolower(Str::random(8));
        }
        $user->password = bcrypt($user->default_password);
        if($user->save()){
            $this->alert('success', 'Berhasil', [
                'text' => 'Password Pengguna berhasil direset'
            ]);
            $this->emit('close-modal');
            $this->resetPage();
        } else {
            $this->alert('error', 'Gagal', [
                'text' => 'Password Pengguna gagal direset. Silahkan coba beberapa saat lagi!'
            ]);
        }
    }
}
