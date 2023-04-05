<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Mst_wilayah;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use Validator;

class LoginController extends Controller
{
    protected $redirectTo = '/';
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function findUsername()
    {
        $login = request()->input('email');
		$messages = [
			'email.required' => 'Email tidak boleh kosong',
		];
		$validator = Validator::make(request()->all(), [
			'email' => 'required|exists:users,nuptk',
		 ],
		$messages
		);
		if ($validator->fails()) {
			$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';
			
		} else {
			$fieldType = 'nuptk';
		}
		request()->merge([$fieldType => $login]);
        return $fieldType;
        /*
        $login = request()->input('login');
 
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
 
        request()->merge([$fieldType => $login]);
 
        return $fieldType;*/
    }
    public function username()
    {
        return $this->username;
    }
    public function show_login_form()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        $data = [
            'semester' => Semester::whereHas('tahun_ajaran', function($query){
                $query->where('periode_aktif', 1);
              })->orderBy('semester_id', 'DESC')->get()
        ];
        return view('auth.login')->with($data);
    }
    public function process_login(Request $request)
    {
        /*$request->validate([
            'email' => 'required',
            'password' => 'required',
            'semester' => 'required'
        ]);*/
        $messages = [
			'email.required' => 'Email tidak boleh kosong',
            'email.exists' => 'Email tidak terdaftar',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong'
		];
		$validator = Validator::make(request()->all(), [
			'email' => 'required|email|exists:users',
            'password' => 'required',
		 ],
		$messages
		)->validate();
        /*$login = $request->email;
		if ($validator->fails()) {
			$fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';
			
		} else {
			$fieldType = 'nuptk';
		}*/
        $fieldType = 'email';
		if (auth()->attempt([$fieldType => $request->email, 'password' => $request->password])) {
            $semester = Semester::where('semester_id', $request->semester)->first();
            $request->session()->put('semester_id', $semester->nama);
            $request->session()->put('semester_aktif', $semester->semester_id);
            $sekolah_id = (auth()->user()->sekolah_id) ? auth()->user()->sekolah_id : NULL;
            $nama_sekolah = (auth()->user()->sekolah_id) ? auth()->user()->sekolah->nama : '-';
            $request->session()->put('sekolah_id', $sekolah_id);
            $request->session()->put('nama_sekolah', $nama_sekolah);
            $request->session()->put('user_id', auth()->user()->user_id);
            $request->session()->put('guru_id', auth()->user()->guru_id);
            $request->session()->put('peserta_didik_id', auth()->user()->peserta_didik_id);
            $user = auth()->user();
            $user->last_login_at = date('Y-m-d H:i:s');
            $user->last_login_ip = $request->ip();
            $user->save();
            if(!$user->peserta_didik_id && !$user->guru_id){
                $team = Team::updateOrCreate([
                    'name' => $semester->nama,
                    'display_name' => $semester->nama,
                    'description' => $semester->nama,
                ]);
                if(!$user->hasRole('admin', $semester->nama)){
                    $user->attachRole('admin', $team);
                }
            }
            return redirect()->route('index');
        }
        session()->flash('status', 'Password salah');
        return redirect()->back();
    }
    public function show_signup_form()
    {
        return view('auth.register');
    }
    public function process_signup(Request $request)
    {
        $request->validate(
            [
                'npsn' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|confirmed'
            ],
            [
                'npsn.required' => 'NPSN Tidak boleh kosong!',
                'email.unique' => 'Email sudah terdaftar!',
                'email.required' => 'Email Dapodik Tidak boleh kosong!',
                'password.required' => 'Password Dapodik Tidak boleh kosong!',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.!',
            ]
        );
        try {
            $data_sync = [
                'npsn' => $request->npsn,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $response = Http::post('http://app.erapor-smk.net/api/sync/register', $data_sync);
            //Http::withBasicAuth('admin', '1234')->asForm()->post('http://app.erapor-smk.net/api/register', $data_sync);
            $data = $response->object();
            if($response->successful()){
                return $this->create_user($data, $request->email, $request->password);
            } else {
                session()->flash('status', $data->message);
                return redirect()->route('register');
            }
        } catch (\Exception $e){
            session()->flash('status', 'Registrasi gagal. Server pusat tidak merespon');
            return redirect()->route('register');
            $this->error($e->getMessage());
        }
    }
    private function create_user($data, $email, $password){
        if(!$data->data){
            session()->flash('status', $data->message);
            return redirect()->route('register');
        }
        $set_data = $data->data->sekolah;
        if($set_data->bentuk_pendidikan_id == '15'){
            $get_kode_wilayah = $set_data->wilayah;
            $kode_wilayah = $set_data->kode_wilayah;
            $kecamatan = '-';
            $kabupaten = '-';
            $provinsi = '-';
            if($get_kode_wilayah){
                $kode_wilayah = $get_kode_wilayah->kode_wilayah;
                if($get_kode_wilayah->parrent_recursive){
                    $kecamatan = $get_kode_wilayah->parrent_recursive->nama;
                    if($get_kode_wilayah->parrent_recursive->parrent_recursive){
                        $kabupaten = $get_kode_wilayah->parrent_recursive->parrent_recursive->nama;
                        if($get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive){
                            $provinsi = $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama;
                            Mst_wilayah::updateOrCreate(
                                [
                                    'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->kode_wilayah,
                                ],
                                [
                                    'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->nama,
                                    'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->id_level_wilayah,
                                    'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                    'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->parrent_recursive->negara_id,
                                    'last_sync' => now(),
                                ]
                            );
                        }
                        Mst_wilayah::updateOrCreate(
                            [
                                'kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->kode_wilayah,
                            ],
                            [
                                'nama' => $get_kode_wilayah->parrent_recursive->parrent_recursive->nama,
                                'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->id_level_wilayah,
                                'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->parrent_recursive->mst_kode_wilayah,
                                'negara_id' => $get_kode_wilayah->parrent_recursive->parrent_recursive->negara_id,
                                'last_sync' => now(),
                            ]
                        );
                    }
                    Mst_wilayah::updateOrCreate(
                        [
                            'kode_wilayah' => $get_kode_wilayah->parrent_recursive->kode_wilayah,
                        ],
                        [
                            'nama' => $get_kode_wilayah->parrent_recursive->nama,
                            'id_level_wilayah' => $get_kode_wilayah->parrent_recursive->id_level_wilayah,
                            'mst_kode_wilayah' => $get_kode_wilayah->parrent_recursive->mst_kode_wilayah,
                            'negara_id' => $get_kode_wilayah->parrent_recursive->negara_id,
                            'last_sync' => now(),
                        ]
                    );
                }
                Mst_wilayah::updateOrCreate(
                    [
                        'kode_wilayah' => $get_kode_wilayah->kode_wilayah,
                    ],
                    [
                        'nama' => $get_kode_wilayah->nama,
                        'id_level_wilayah' => $get_kode_wilayah->id_level_wilayah,
                        'mst_kode_wilayah' => $get_kode_wilayah->mst_kode_wilayah,
                        'negara_id' => $get_kode_wilayah->negara_id,
                        'last_sync' => now(),
                    ]
                );
            }
            $sekolah = Sekolah::updateOrCreate(
                ['sekolah_id' => $set_data->sekolah_id],
                [
                    'npsn' 					=> $set_data->npsn,
                    'nss' 					=> $set_data->nss,
                    'nama' 					=> $set_data->nama,
                    'alamat' 				=> $set_data->alamat_jalan,
                    'desa_kelurahan'		=> $set_data->desa_kelurahan,
                    'kode_wilayah'			=> $kode_wilayah,
                    'kecamatan' 			=> $kecamatan,
                    'kabupaten' 			=> $kabupaten,
                    'provinsi' 				=> $provinsi,
                    'kode_pos' 				=> $set_data->kode_pos,
                    'lintang' 				=> $set_data->lintang,
                    'bujur' 				=> $set_data->bujur,
                    'no_telp' 				=> $set_data->nomor_telepon,
                    'no_fax' 				=> $set_data->nomor_fax,
                    'email' 				=> $set_data->email,
                    'website' 				=> $set_data->website,
                    'status_sekolah'		=> $set_data->status_sekolah,
                    'last_sync'				=> now(),
                ]
            );
            $semester = Semester::where('periode_aktif', 1)->first();
            $user = User::create([
                'sekolah_id' => $sekolah->sekolah_id,
                'name' => 'Administrator',
                'email' => $email,
                'password' => bcrypt($password),
            ]);
            $adminRole = Role::where('name', 'admin')->first();
            $team = Team::updateOrCreate([
                'name' => $semester->nama,
                'display_name' => $semester->nama,
                'description' => $semester->nama,
            ]);
            $user->attachRole($adminRole, $team);
            session()->flash('success', 'Registrasi berhasil');
            return redirect()->route('login');
        } else {
            session()->flash('status', 'Jenjang Sekolah Salah');
            return redirect()->route('register');
        }
    }
    public function logout(Request $request)
    {
        /*
        $request->session()->put('semester_id', $semester->nama);
            $request->session()->put('semester_aktif', $semester->semester_id);
            $sekolah_id = (auth()->user()->sekolah_id) ? auth()->user()->sekolah_id : NULL;
            $nama_sekolah = (auth()->user()->sekolah_id) ? auth()->user()->sekolah->nama : '-';
            $request->session()->put('sekolah_id', $sekolah_id);
            $request->session()->put('nama_sekolah', $nama_sekolah);
            $request->session()->put('user_id', auth()->user()->user_id);
            $request->session()->put('guru_id', auth()->user()->guru_id);
            $request->session()->put('peserta_didik_id', auth()->user()->peserta_didik_id);
        */
        Auth::logout();
        $theme = session('theme');
        $request->session()->invalidate();
        if($theme){
            $request->session()->put('theme', $theme);
        }
        //$request->session()->forget(['semester_id', 'semester_aktif', 'sekolah_id', 'nama_sekolah', 'user_id', 'guru_id', 'peserta_didik_id']);
        //$request->session()->regenerate();
        return redirect()->route('login');
    }
}
