<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dingo\Api\Exception\Handler;
use Illuminate\Auth\AuthenticationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $handler = app(Handler::class);
        $handler->register(function(AuthenticationException
                                    $exception){
            return response()->json(['error' => 'Unauthenticated'],
                401);
        });
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
