<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
//tambahan
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LoginSuccessful;
use App\Listeners\LogSuccessfulLogout;
use App\Listeners\RegisterSuccessful;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            //RegisterSuccessful::class,
            SendEmailVerificationNotification::class,
        ],
        /*Login::class => [
            LoginSuccessful::class
        ],
        Logout::class => [
            LogSuccessfulLogout::class
        ],*/
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
