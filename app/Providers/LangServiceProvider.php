<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Session;

class LangServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        # TODO: come back to this
        #if(IPLocate::localize($_SERVER['REMOTE_ADDR'] == 'no')){
        #    $lang = 'no';
        #}else{
        #    $lang = 'en';
        #}

        $lang = 'no';

        $jslang = '<script type="text/javascript"> Lang.setLocale(\''.$lang.'\'); </script>';
        View::share('jslang', $jslang);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
