<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Features;
use App\Models\Sekolah;
use Carbon\Carbon;
use Config;
use File;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = base_path('bootstrap/cache');
        $files = File::files($path);
        $config = FALSE;
        $config_ = FALSE;
        foreach($files as $file){
            if($file->getRelativePathname() == 'config-.php'){
                $config_ = $file->getPathname();
            }
            if($file->getRelativePathname() == 'config.php'){
                $config = $file->getPathname();
            }
        }
        if($config_ && $config){
            File::move($config_,$config);
        } elseif($config_ && !$config){
            File::move($config_,$path.'/config.php');
        }
        if(Schema::hasTable('sekolah') && !Sekolah::first()){
            Config::set('erapor.registration', TRUE);
        }
        Carbon::setLocale(LC_TIME, $this->app->getLocale());
        Validator::excludeUnvalidatedArrayKeys();
        Paginator::useBootstrap();
    }
}
