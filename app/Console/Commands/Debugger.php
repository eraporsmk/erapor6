<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Semester;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\User;
use App\Models\Sekolah;
use DB;
use App\Models\Nilai;

class Debugger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = 'firdausahmadyassek@gmail.com';
        $password = 12345678;
        $user = User::where('email', $email)->update(['password' => bcrypt($password)]);
    }
}
