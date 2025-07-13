<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sosmed;
use Illuminate\Support\Facades\Hash;

class SosmedData extends Seeder
{
    public function run()
    {
        Sosmed::insert([
            [
                'icon' => 'fa-brands fa-facebook',
                'name' => 'facebook'
            ],
            [
                'icon' => 'fa-brands fa-x-twitter',
                'name' => 'twitter'
            ],
             [
                'icon' => 'fa-brands fa-instagram',
                'name' => 'instagram'
            ],
             [
                'icon' => 'fa-brands fa-youtube',
                'name' => 'youtube'
            ],
        ]);
    }
}
