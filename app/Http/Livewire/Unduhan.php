<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Unduhan extends Component
{
    public function render()
    {
        return view('livewire.unduhan', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['name' => "Pusat Unduhan"]
            ],
            'collection' => [
                [
                    'collection' => [
                        [
                            'title' => 'Panduan Pembelajaran dan Asesmen',
                            'url' => 'Panduan-Pembelajaran-dan-Asesmen.pdf'
                        ],
                        [
                            'title' => 'Panduan <br>Penguatan Projek Profil Pancasila',
                            'url' => 'Panduan-Penguatan-Projek-Profil-Pancasila.pdf'
                        ]
                    ],
                ],
                [
                    'collection' => [
                        [
                            'title' => 'Panduan Penggunaan <br>e-Rapor SMK v.6 (Kurikulum Merdeka)',
                            'url' => 'Panduan-Penggunaan-e-Rapor-SMK-v.6-kurikulum-merdeka.pdf'
                        ],
                        [
                            'title' => 'Panduan Penggunaan <br>e-Rapor SMK v.6 (Kurikulum 2013 REV)',
                            'url' => 'Panduan-Penggunaan-e-Rapor-SMK-v.6-k13-rev.pdf'
                        ]
                    ],
                ]
            ],
        ]);
    }
}
