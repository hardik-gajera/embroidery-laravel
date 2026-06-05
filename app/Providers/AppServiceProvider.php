<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            View::share('appSettings', [
                'company_name' => Setting::get('company_name', 'Aaradhya Design Gallery'),
                'mobile' => Setting::get('mobile', '+91 98765 43210'),
                'email' => Setting::get('email', 'support@embroidery.com'),
                'address' => Setting::get('address', 'Surat, Gujarat, India'),
                'whatsapp' => Setting::get('whatsapp', '+91 98765 43210'),
                'facebook_url' => Setting::get('facebook_url', '#'),
                'instagram_url' => Setting::get('instagram_url', '#'),
                'logo' => Setting::get('logo'),
            ]);
        }
    }
}
