<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Mst_wilayah;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;

class RegisterSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        /*
        $user = $event->user;
        $semester = Semester::where('periode_aktif', 1)->first();
        $adminRole = Role::where('name', 'admin')->first();
        $team = Team::updateOrCreate([
            'name' => $semester->nama,
            'display_name' => $semester->nama,
            'description' => $semester->nama,
        ]);
        if(!$user->hasRole($adminRole, $semester->semester_id)){
            $user->attachRole($adminRole, $team);
        }
        */
        $user = $event->user;
        $get_sekolah = Http::get('http://103.40.55.242/erapor_server/sync/get_sekolah/'.$user->name);
        $data_sekolah = $get_sekolah->object();
        if($data_sekolah){
            $data_sekolah = $data_sekolah->data;
            $data = NULL;
            foreach($data_sekolah as $user_dapodik){
                if($user_dapodik->username == $user->email){
                    $data = $user_dapodik;
                }
            }
            if($data){
                if (!Hash::check($this->request->password, $data->password)) {
                    Auth::logout();
                    $user->delete();
                    $this->request->session()->put('status', 'Password Salah');
                    return redirect('/register');
                }
                $semester = Semester::where('periode_aktif', 1)->first();
                $data_sync = [
                    'username_dapo'		=> $data->username,
                    'password_dapo'		=> $data->password_lama,
                    'npsn'				=> $data->npsn,
                    'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                    'semester_id'		=> $semester->semester_id,
                    'sekolah_id'		=> $data->sekolah_id,
                ];
                $response = Http::withHeaders([
                    'x-api-key' => $data->sekolah_id,
                ])->withBasicAuth('admin', '1234')->asForm()->post('http://103.40.55.242/erapor_server/api/register', $data_sync);
                $sekolah = $response->object();
                $set_data = $sekolah->data;
                if($set_data->bentuk_pendidikan_id == '15'){
                    $get_kode_wilayah = $set_data->wilayah;//Mst_wilayah::with(['parrentRecursive'])->find($set_data->kode_wilayah);
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
                    $user->sekolah_id = $sekolah->sekolah_id;
                    $user->name = 'Administrator';
                    $adminRole = Role::where('name', 'admin')->first();
                    $team = Team::updateOrCreate([
                        'name' => $semester->nama,
                        'display_name' => $semester->nama,
                        'description' => $semester->nama,
                    ]);
                    if(!$user->hasRole($adminRole, $semester->semester_id)){
                        $user->attachRole($adminRole, $team);
                    }
                    $user->save();
                    $this->request->session()->put('semester_id', $semester->nama);
                    $this->request->session()->put('semester_aktif', $semester->semester_id);
                    $sekolah_id = ($user->sekolah_id) ? $user->sekolah_id : NULL;
                    $nama_sekolah = ($user->sekolah_id) ? $user->sekolah->nama : '-';
                    $this->request->session()->put('sekolah_id', $sekolah_id);
                    $this->request->session()->put('nama_sekolah', $nama_sekolah);
                } else {
                    Auth::logout();
                    $user->delete();
                    //session(['status' => 'Jenjang Sekolah Salah']);
                    //$this->request->session()->put('status', 'Jenjang Sekolah Salah');
                    $this->request->session()->flash('status', 'Jenjang Sekolah Salah');
                    return redirect('/login');
                }
            } else {
                Auth::logout();
                $user->delete();
                //session(['status' => 'Email tidak terdaftar']);
                $this->request->session()->put('status', 'Email tidak terdaftar');
                //$this->request->session()->flash('status', 'Email tidak terdaftar');
                return redirect('/login');
            }
        } else {
            Auth::logout();
            $user->delete();
            //session(['status' => 'Email tidak terdaftar']);
            $this->request->session()->put('status', 'NPSN tidak ditemukan');
            //$this->request->session()->flash('status', 'Email tidak terdaftar');
            return redirect('/login');
        }
        Auth::logout();
        $this->request->session()->flash('success', 'Registrasi berhasil');
        //session(['status' => '']);
    }
}
