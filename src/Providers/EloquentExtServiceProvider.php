<?php 

namespace Limanweb\EloquentExt\Providers;

use Illuminate\Support\ServiceProvider;

class EloquentExtServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'eloquent-extensions');
        
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/eloquent-extensions'),
            __DIR__.'/../config/eloquent_ext.php' => config_path('eloquent_ext.php'),
        ]);
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}