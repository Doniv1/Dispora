<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'id_setting' => 1,
            'logo' => 'logo.png',
            'logo_white' => 'logo_white.png',
            'icon' => 'icon.png',
            'icon_white' => '',
            'meta_description' => '',
            'meta_address' => '',
            'updated_at' => now(),
        ]);
    }
}
