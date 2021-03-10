<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Rules\InRange;
use Laravel\Dusk\DuskServiceProvider;

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
        if ($this->app->environment('local', 'testing', 'staging')) {
           $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        /****************************** */
        /****** Form Validations ****** */
        /****************************** */
        Validator::extend('in_range', function ($attribute, $value, $parameters, $validator) {
            return (new InRange())->passes($attribute, $value, $parameters);
        });
        Validator::replacer('in_range', function ($message, $attribute, $rule, $parameters) {
            return str_replace(
                [':attribute', ':min', ':max'],
                [$attribute, $parameters[0], $parameters[1]],
                $message
            );
        });
    }
}
