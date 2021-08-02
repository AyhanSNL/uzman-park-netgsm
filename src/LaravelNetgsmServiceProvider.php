<?php

namespace Noxarc\Netgsm;

use Illuminate\Support\ServiceProvider;

class LaravelNetgsmServiceProvider extends ServiceProvider
{
	public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/netgsm.php' => config_path('netgsm.php'),
        ], 'laravel-netgsm-config');

        $this->publishes([
        	__DIR__.'/Migrations/' => database_path('migrations')
        ], 'laravel-netgsm-migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'netgsm');

        $this->publishes([
	        __DIR__.'/../resources/lang/' => resource_path('lang/vendor/netgsm'),
	    ]);
    }
}
