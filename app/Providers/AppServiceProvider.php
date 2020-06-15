<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use \Carbon\Carbon;
use App\Sign;
use App\Rest;

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
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view) {
            if(auth()->user())
                $user = auth()->user()->id;
            if(isset($user)){
                $date = Carbon::now();
                $today = $date->isoFormat('YYYY-MM-DD');
                $signed = Sign::where('user_id', $user)->whereDate('date', $today)->orderBy('created_at', 'DESC')->first();
                $status  = "Tocando los huevos";
                if (!isset($signed)) {
                    $status = 'No trabajando ';
                } else {
                    if (!isset($signed->out)) {
                        $rest = Rest::where('sign_id', $signed->id)->orderBy('created_at', 'DESC')->first();
                        if (!isset($rest)) {
                            $status = 'Trabajando';
                        } else {
                            if (!isset($rest->in)) {
                                $status = 'Descansando';
                            } else {
                                $status = 'Trabajando';
                            }
                        }
                    } else {
                        $status = "Finalizado día";
                    }
                }
            }
            $view->with('status', isset($status) ? $status : "");
        });
    }
}
