<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //$menu->add($a['text'], $a['url'])->id($a['id'])->data(['role' => $a['role'], 'smt' => $a['smt'], 'cara_penilaian' => $a['cara_penilaian']])->append($this->setAppend())->prepend($this->icon($a['icon']))->link->attr($a['attr']);
        // $menu->{Str::camel(Str::ascii($a['text']))}->add($submenu['text'], ['url' => $submenu['url'], 'parent' => $menu->{$submenu['id']}->id])->data(['role' => $submenu['role'], 'smt' => $submenu['smt'], 'cara_penilaian' => $submenu['cara_penilaian']])->append($this->setAppend())->prepend($this->icon($submenu['icon']))->link->attr($submenu['attr']);
        \Menu::make('MyNavBar', function($menu){
            $menu->add('Beranda',     ['route'  => 'index'])->data([
                'role' => ['admin', 'guru', 'siswa'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('home'))->link->attr($this->text_class());
            $menu->group([], function($menu){
                $menu->add('Sinkronisasi', 'javascript:void(0)')->data([
                    'role' => ['admin'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('refresh'))->nickname('sinkronisasi')->link->attr($this->text_class());
                $menu->group(['prefix' => 'sinkronisasi'], function($menu){
                    $menu->sinkronisasi->add('Dapodik', 'dapodik')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('download'))->link->attr($this->text_class());
                    $menu->sinkronisasi->add('e-Rapor', 'erapor')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('upload'))->link->attr($this->text_class());
                });
            });
            $menu->group([], function($menu){
                $menu->add('Pengaturan', 'javascript:void(0)')->data([
                    'role' => ['admin'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('wrench'))->nickname('pengaturan')->link->attr($this->text_class());
                $menu->group(['prefix' => 'setting'], function($menu){
                    $menu->pengaturan->add('Umum', 'umum')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('gear'))->link->attr($this->text_class());
                    $menu->pengaturan->add('Akses Pengguna', 'users')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('user-lock'))->link->attr($this->text_class());
                });
            });
            $menu->group(['prefix' => 'referensi'], function($menu){
                $menu->add('Referensi', 'javascript:void(0)')->data([
                    'role' => ['admin', 'guru'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('list'))->nickname('referensi')->link->attr($this->text_class());
                $menu->group([], function($menu){
                    $menu->referensi->add('Referensi GTK', 'javascript:void(0)')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->nickname('referensi_gtk')->link->attr($this->text_class());
                    $menu->group([], function($menu){
                        $menu->referensi_gtk->add('Guru', 'guru')->data([
                            'role' => ['admin'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('graduation-cap'))->link->attr($this->text_class());
                        $menu->referensi_gtk->add('Tendik', 'tendik')->data([
                            'role' => ['admin'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('graduation-cap'))->link->attr($this->text_class());
                        $menu->referensi_gtk->add('Instruktur', 'instruktur')->data([
                            'role' => ['admin'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('graduation-cap'))->link->attr($this->text_class());
                        $menu->referensi_gtk->add('Asesor', 'asesor')->data([
                            'role' => ['admin'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('graduation-cap'))->link->attr($this->text_class());
                    });
                    $menu->referensi->add('Rombongan Belajar', 'javascript:void(0)')->data([
                        'role' => ['admin', 'waka'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->nickname('rombel')->link->attr($this->text_class());
                    $menu->group([], function($menu){
                        $menu->rombel->add('Reguler', 'rombongan-belajar')->data([
                            'role' => ['admin', 'waka'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->rombel->add('Matpel Pilihan', 'rombel-pilihan')->data([
                            'role' => ['admin', 'waka'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                    $menu->referensi->add('Peserta Didik', 'javascript:void(0)')->data([
                        'role' => ['admin', 'guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->nickname('pd')->link->attr($this->text_class());
                    $menu->group([], function($menu){
                        $menu->pd->add('Peserta Didik Aktif', 'peserta-didik-aktif')->data([
                            'role' => ['admin', 'guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->pd->add('Peserta Didik Keluar', 'peserta-didik-keluar')->data([
                            'role' => ['admin'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class('danger'));
                        $menu->pd->add('Password Peserta Didik', 'password-peserta-didik')->data([
                            'role' => ['wali'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('users'))->link->attr($this->text_class());
                    });
                    $menu->referensi->add('Mata Pelajaran', 'mata-pelajaran')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Ekstrakurikuler', 'ekstrakurikuler')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Teknik Penilaian', 'teknik-penilaian')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Acuan Sikap', 'acuan-sikap')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Kompetensi Dasar', 'kompetensi-dasar')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Capaian Pembelajaran', 'capaian-pembelajaran')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Tujuan Pembelajaran', 'tujuan-pembelajaran')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('Uji Kompetensi Keahlian', 'uji-kompetensi-keahlian')->data([
                        'role' => ['kaprog'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->referensi->add('DUDI', 'dudi')->data([
                        'role' => ['admin'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                });
            });
            $menu->add('Nilai Akhir', 'penilaian/nilai-akhir')->data([
                'role' => ['guru'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('list-check'))->nickname('induk')->link->attr($this->text_class());
            $menu->add('Capaian Kompetensi', 'penilaian/capaian-kompetensi')->data([
                'role' => ['guru'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('check-double'))->nickname('induk')->link->attr($this->text_class());
            $menu->group([], function($menu){
                $menu->add('Penilaian Projek', 'javascript:void(0)')->data([
                    'role' => ['guru-p5'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('file-circle-check'))->nickname('penilaian_projek')->link->attr($this->text_class());
                $menu->group([], function($menu){
                    $menu->penilaian_projek->add('Perencanaan', 'perencanaan/projek-profil-pelajar-pancasila-dan-budaya-kerja')->data([
                        'role' => ['guru-p5'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->penilaian_projek->add('Penilaian', 'penilaian/projek-profil-pelajar-pancasila-dan-budaya-kerja')->data([
                        'role' => ['guru-p5'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                });
            });
            $menu->add('Nilai Ekstrakurikuler', 'penilaian/ekstrakurikuler')->data([
                'role' => ['pembina_ekskul'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
            /*$menu->add('Nilai UKK', 'penilaian/ukk')->data([
                'role' => ['internal'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());*/
            $menu->group([], function($menu){
                $menu->add('Wali Kelas', 'javascript:void(0)')->data([
                    'role' => ['wali', 'waka'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('copy'))->nickname('wali_kelas')->link->attr($this->text_class());
                $menu->group(['prefix' => 'wali-kelas'], function($menu){
                    $menu->wali_kelas->add('Ketidakhadiran', 'ketidakhadiran')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    /*$menu->wali_kelas->add('Prestasi Peserta Didik', 'prestasi-pd')->data([
                        'role' => ['wali'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());*/
                    $menu->wali_kelas->add('Cetak Rapor', 'rapor-nilai-akhir')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->wali_kelas->add('Unduh Leger', 'leger')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('download'))->link->attr($this->text_class());
                });
            });
            $menu->group([], function($menu){
                $menu->add('Kurikulum Merdeka', 'javascript:void(0)')->data([
                    'role' => ['guru'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap'])
                ])->append($this->setAppend())->prepend($this->icon('school-flag'))->nickname('kurmer')->link->attr($this->text_class());
                $menu->group([], function($menu){
                    $menu->kurmer->add('Rencana Penilaian', 'javascript:void(0)')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap'])
                    ])->append($this->setAppend())->prepend($this->icon('list-check'))->nickname('perencanaan_kurmer')->link->attr($this->text_class());
                    $menu->group(['prefix' => 'perencanaan'], function($menu){
                        $menu->perencanaan_kurmer->add('Penilaian Akademik', 'penilaian-kurikulum-merdeka')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                    $menu->group(['prefix' => 'perencanaan'], function($menu){
                        $menu->perencanaan_kurmer->add('Penilaian Projek', 'projek-profil-pelajar-pancasila-dan-budaya-kerja')->data([
                            'role' => ['guru-p5'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                    $menu->kurmer->add('Proses Penilaian', 'javascript:void(0)')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap'])
                    ])->append($this->setAppend())->prepend($this->icon('edit'))->nickname('penilaian_kurmer')->link->attr($this->text_class());
                    $menu->group(['prefix' => 'penilaian'], function($menu){
                        $menu->penilaian_kurmer->add('Nilai Akademik', 'kurikulum-merdeka')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurmer->add('Nilai Projek', 'projek-profil-pelajar-pancasila-dan-budaya-kerja')->data([
                            'role' => ['guru-p5'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                });
            });
            $menu->group([], function($menu){
                $menu->add('Kurikulum 2013 REV', 'javascript:void(0)')->data([
                    'role' => ['guru'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('building-flag'))->nickname('kurtilas')->link->attr($this->text_class());
                $menu->group([], function($menu){
                    $menu->kurtilas->add('Perencanaan', 'javascript:void(0)')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('list-check'))->nickname('perencanaan_kurtilas')->link->attr($this->text_class());
                    $menu->group(['prefix' => 'perencanaan'], function($menu){
                        $menu->perencanaan_kurtilas->add('Rasio Nilai Akhir', 'rasio-nilai-akhir')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->perencanaan_kurtilas->add('Pengetahuan', 'penilaian-pengetahuan')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->perencanaan_kurtilas->add('Keterampilan', 'penilaian-keterampilan')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->perencanaan_kurtilas->add('Bobot Keterampilan', 'bobot-keterampilan')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->perencanaan_kurtilas->add('Penilaian UKK', 'penilaian-ukk')->data([
                            'role' => ['kaprog'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                    $menu->kurtilas->add('Penilaian', 'javascript:void(0)')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('edit'))->nickname('penilaian_kurtilas')->link->attr($this->text_class());
                    $menu->group(['prefix' => 'penilaian'], function($menu){
                        $menu->penilaian_kurtilas->add('Pengetahuan', 'pengetahuan')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurtilas->add('Keterampilan', 'keterampilan')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurtilas->add('Sikap', 'sikap')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurtilas->add('Remedial', 'remedial')->data([
                            'role' => ['guru'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurtilas->add('Nilai Ekstrakurikuler', 'ekstrakurikuler')->data([
                            'role' => ['pembina_ekskul'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                        $menu->penilaian_kurtilas->add('Nilai UKK', 'ukk')->data([
                            'role' => ['internal'], 
                            'smt' => collect([1,2]),
                            'cara_penilaian' => collect(['lengkap', 'sederhana'])
                        ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    });
                });
            });
            $menu->group([], function($menu){
                $menu->add('Laporan Hasil Belajar', 'javascript:void(0)')->data([
                    'role' => ['wali', 'waka'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('copy'))->nickname('laporan')->link->attr($this->text_class());
                $menu->group(['prefix' => 'laporan'], function($menu){
                    $menu->laporan->add('Nilai US/USBN', 'nilai-us')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Nilai UN', 'nilai-un')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Kewirausahaan', 'kewirausahaan')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Catatan Akademik', 'catatan-akademik')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Nilai Karakter', 'nilai-karakter')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Ketidakhadiran', 'ketidakhadiran')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Nilai Ekstrakurikuler', 'nilai-ekstrakurikuler')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Praktik Kerja Industri', 'prakerin')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Prestasi Peserta Didik', 'prestasi-pd')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Kenaikan Kelas', 'kenaikan-kelas')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->laporan->add('Cetak Rapor UTS', 'rapor-uts')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('print'))->link->attr($this->text_class());
                    $menu->laporan->add('Cetak Rapor Semester', 'rapor-semester')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('print'))->link->attr($this->text_class());
                    /*$menu->laporan->add('Cetak Rapor P5', 'projek-profil-pelajar-pancasila-dan-budaya-kerja')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap'])
                    ])->append($this->setAppend())->prepend($this->icon('print'))->link->attr($this->text_class());*/
                    $menu->laporan->add('Unduh Leger', 'leger')->data([
                        'role' => ['wali', 'waka'], 
                        'smt' => collect([1, 2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('download'))->link->attr($this->text_class());
                });
            });
            $menu->group([], function($menu){
                $menu->add('Monitoring Dan Analisis', 'javascript:void(0)')->data([
                    'role' => ['guru'], 
                    'smt' => collect([1,2]),
                    'cara_penilaian' => collect(['lengkap', 'sederhana'])
                ])->append($this->setAppend())->prepend($this->icon('eye'))->nickname('monitoring')->link->attr($this->text_class());
                $menu->group(['prefix' => 'monitoring'], function($menu){
                    $menu->monitoring->add('Rekap Nilai', 'rekap-nilai')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->monitoring->add('Analisis Hasil Penilaian', 'analisis-nilai')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->monitoring->add('Analisis Hasil Remedial', 'analisis-remedial')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->monitoring->add('Pencapaian Kompetensi', 'capaian-kompetensi')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                    $menu->monitoring->add('Prestasi Individu PD', 'prestasi-individu')->data([
                        'role' => ['guru'], 
                        'smt' => collect([1,2]),
                        'cara_penilaian' => collect(['lengkap', 'sederhana'])
                    ])->append($this->setAppend())->prepend($this->icon('hand-point-right'))->link->attr($this->text_class());
                });
            });
            $menu->add('Profil Pengguna', 'user/profile')->data([
                'role' => ['admin', 'guru', 'siswa'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('user'))->link->attr($this->text_class());
            $menu->add('Pusat Unduhan', 'unduhan')->data([
                'role' => ['admin', 'guru', 'siswa'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('download'))->link->attr($this->text_class());
            $menu->add('Daftar Perubahan', 'changelog')->data([
                'role' => ['admin'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('laptop-code'))->link->attr($this->text_class());
            $menu->add('Cek Pembaharuan', 'check-update')->data([
                'role' => ['admin'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('terminal'))->link->attr($this->text_class());
            $menu->add('Keluar Aplikasi', ['url'  => 'logout'])->data([
                'role' => ['admin', 'guru', 'siswa'], 
                'smt' => collect([1,2]),
                'cara_penilaian' => collect(['lengkap', 'sederhana'])
            ])->append($this->setAppend())->prepend($this->icon('right-from-bracket'))->link->attr($this->text_class('danger', 'event.preventDefault(); document.getElementById(\'logout-form\').submit();'));
        })->filter(function($item) use ($request){
            $user = auth()->user();
            $semester = $request->session()->get('semester_id');
            $semester_aktif = Str::substr($request->session()->get('semester_aktif'), 4, 1);
            $cara_penilaian = 'sederhana';
            //config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian');
            if($cara_penilaian && $item->data('cara_penilaian')){
                if(
                    $user 
                    && $user->hasRole( $item->data('role'), $semester) 
                    && $item->data('smt')->contains($semester_aktif) 
                    && $item->data('cara_penilaian')->contains($cara_penilaian)
                )
                {
                    return true;
                }
            } else {
                if($user && $user->hasRole( $item->data('role'), $semester) && $item->data('smt')->contains($semester_aktif)){
                    return true;
                }
            }
            return false;
        });
        return $next($request);
    }
    private function submenu($submenu, $a, $menu, $url){
        //, 'parent' => $menu->about->id
        if(isset($submenu['offline'])){
            if($url['host'] == 'localhost' || $url['host'] == 'erapor6.test'){
                $menu->{Str::camel(Str::ascii($a['text']))}->add($submenu['text'], $submenu['url'])->data(['role' => $submenu['role'], 'smt' => $submenu['smt'], 'cara_penilaian' => $submenu['cara_penilaian']])->append($this->setAppend())->prepend($this->icon($submenu['icon']))->link->attr($submenu['attr']);
            }
        } else {
            $menu->{Str::camel(Str::ascii($a['text']))}->add($submenu['text'], ['url' => $submenu['url'], 'parent' => $menu->{$submenu['id']}->id])->data(['role' => $submenu['role'], 'smt' => $submenu['smt'], 'cara_penilaian' => $submenu['cara_penilaian']])->append($this->setAppend())->prepend($this->icon($submenu['icon']))->link->attr($submenu['attr']);
        }
    }
    private function icon($icon){
        return '<i class="fa-solid fa-'.$icon.'"></i><span class="menu-title text-truncate">';
        return '<i data-feather="'.$icon.'"></i><span class="menu-title text-truncate">';
    }
    private function text_class($color = NULL, $onclick = NULL){
        if($onclick){
            if($color){
                return [
                    'class' => 'd-flex align-items-center text-'.$color,
                    'onclick' => $onclick,
                    //'title' => 'asd',
                ];
            } else {
                return [
                    'class' => 'd-flex align-items-center',
                    'onclick' => $onclick,
                ];
            }
        } else {
            if($color){
                return ['class' => 'd-flex align-items-center text-'.$color];
            } else {
                return [
                    'class' => 'd-flex align-items-center',
                    //'title' => 'asd',
                ];
            }
        }
    }
    private function badge($color, $text){
        return '<span class="badge rounded-pill badge-light-'.$color.' ms-auto me-1">'.$text.'</span>';
    }
    private function setAppend($color = NULL, $text = NULL){
        if($color){
            return '</span>'.$this->badge($color, $text);
        } else {
            return '</span>';
        }
    }
}
