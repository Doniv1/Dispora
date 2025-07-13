<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\User;
use App\Models\Sosmed;
use App\Models\Training;
use App\Models\WebPhone;
use App\Models\WebEmail;
use App\Models\Vector;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        require_once app_path('Helpers/globals.php');

        if (Schema::hasTable('settings')) {
            $settings = Setting::where('id_setting', 1)->first();
            View::share('setting', $settings);
            $this->app->singleton('setting', fn () => $settings);
        }

        if (Schema::hasTable('users')) {
            $users = User::where('id_user', 1)->first();
            View::share('user', $users);
            $this->app->singleton('user', fn () => $users);
        }

        if (Schema::hasTable('sosmeds') && Schema::hasTable('sosmed_setting')) {
            $sosmed = Sosmed::select([
                'sosmeds.*',
                DB::raw("(SELECT url FROM sosmed_setting WHERE sosmed_setting.id_sosmed = sosmeds.id_sosmed AND sosmed_setting.id_setting = 1 LIMIT 1) as url"),
                DB::raw("(SELECT name FROM sosmed_setting WHERE sosmed_setting.id_sosmed = sosmeds.id_sosmed AND sosmed_setting.id_setting = 1 LIMIT 1) as name_sosmed"),
            ])->get();

            View::share('sosmed', $sosmed);
            $this->app->singleton('sosmed', fn () => $sosmed);
        }

        if (Schema::hasTable('web_phone')) {
            $web_phone = WebPhone::where('id_setting', 1)->get();
            View::share('web_phone', $web_phone);
            $this->app->singleton('web_phone', fn () => $web_phone);
        }

        if (Schema::hasTable('web_email')) {
            $web_email = WebEmail::where('id_setting', 1)->get();
            View::share('web_email', $web_email);
            $this->app->singleton('web_email', fn () => $web_email);
        }

        if (Schema::hasTable('vectors')) {
            $vector = Vector::where('status', 'Y')->get();
            View::share('vector', $vector);
            $this->app->singleton('vector', fn () => $vector);
        }

        if (Schema::hasTable('trainings')) {
            $training = Training::where('status', 'Y')->get();
            View::share('training', $training);
            $this->app->singleton('training', fn () => $training);
        }
    }
}
