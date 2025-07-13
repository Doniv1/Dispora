<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ambil data JSON
    $json = File::get(database_path('seeders/data_users.json'));
    $users = json_decode($json, true);

    $inserted = 0;
    $skipped = 0;

    foreach ($users as $user) {
      $exists = DB::table('users')
        ->where('email', $user['email'])
        ->orWhere('phone', $user['phone'])
        ->exists();

      if (!$exists) {
        DB::table('users')->insert($user);
      } else {
        Log::info("Data duplikat dilewati (email/phone): " . $user['email'] . ' / ' . $user['phone']);
      }
    }


    echo "✅ Seeder selesai. Inserted: $inserted, Dilewati (duplikat): $skipped\n";
  }
}
