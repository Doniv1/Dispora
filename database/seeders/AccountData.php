<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountData extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'role' => 1,
                'email' => 'admin@gmail.com',
                'phone' => '0811111111111',
                'password' => Hash::make('12345'),
            ]
        ]);
    }
}
