<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Models\Sekolah;
use Carbon\Carbon;
use Config;

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
        if(Schema::hasTable('sekolah') && Sekolah::first()){
            if(is_null(env('REGISTRATION'))){
                Config::set('erapor.registration', FALSE);
            } else {
                Config::set('erapor.registration', env('REGISTRATION'));
            }
        } else {
            Config::set('erapor.registration', TRUE);
        }
        Carbon::setLocale(LC_TIME, $this->app->getLocale());
        Validator::excludeUnvalidatedArrayKeys();
        Paginator::useBootstrap();
    }
}
