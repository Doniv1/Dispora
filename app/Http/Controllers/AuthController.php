<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vector;
use App\Models\UserVector;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

  public function login(Request $request)
  {

    if (!$request->isMethod('post')) {
      return redirect()->route('home');
    }

    $arrVar = [
      'email' => 'Alamat email',
      'password' => 'Kata sandi'
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi input satu per satu (sesuai dengan logika CI3-mu)
    foreach ($arrVar as $var => $label) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_login_' . $var, "$label tidak boleh kosong!"];
        $data['arrAccess'][] = false;
      } else {
        $post[$var] = trim($$var);
        $data['arrAccess'][] = true;
      }
    }

    // Jika ada input yang kosong, return error
    if (in_array(false, $data['arrAccess'])) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return response()->json(['status' => 500, 'alert' => ['message' => 'Email tidak valid! Masukkan email yang valid']]);
    }

    // Cek user berdasarkan email
    $user = User::where('email', $email)->where('deleted', 'N')->first();

    if ($user) {
      // Cek apakah user diblokir
      if ($user->status == 'N') {
        $reason = $user->reason ? ' dengan alasan </br></br><b>"' . $user->reason . '!"</b></br></br>' : '!';
        return response()->json(['status' => 700, 'alert' => ['message' => 'Anda telah di block dari sistem' . $reason . ' Hubungi admin jika terjadi kesalahan']]);
      }

      // Cek password
      if (Hash::check($password, $user->password)) {
        // Set session Laravel
        Auth::login($user);
        $prefix = config('session.prefix');

        // Simpan session
        Session::put([
          "{$prefix}_id_user"  => $user->id_user,
          "{$prefix}_role"  => $user->role,
          "{$prefix}_name"     => $user->name,
          "{$prefix}_email"    => $user->email,
          "{$prefix}_phone"    => $user->phone,
          "{$prefix}_image"    => $user->image,
        ]);


        return response()->json([
          'status' => 200,
          'alert' => ['message' => 'Berhasil masuk! Selamat datang ' . $user->name],
          'reload' => true
        ]);
      } else {
        return response()->json(['status' => 500, 'alert' => ['message' => 'Kata sandi salah! Masukkan kata sandi yang tepat']]);
      }
    } else {
      return response()->json(['status' => 500, 'alert' => ['message' => 'Email tidak terdaftar dalam sistem!']]);
    }
  }

  public function register1(Request $request)
  {
    if (!$request->isMethod('post')) {
      return redirect()->route('home');
    }

    $arrVar = [


      'name' => 'Nama',
      'born_date' => 'Tanggal Lahir',
      'education_status' => 'Status Pendidikan',
      'gender' => 'Jenis Kelamin',
      'email' => 'Alamat email',
      'phone' => 'Nomor telepon',
      'password' => 'Kata sandi',
      'repassword' => 'Konfirmasi kata sandi',
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi input satu per satu (sesuai dengan logika CI3-mu)
    foreach ($arrVar as $var => $label) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$label tidak boleh kosong!"];
        $data['arrAccess'][] = false;
      } else {
        if (!in_array($var, ['repassword'])) {
          $post[$var] = trim($$var);
        }

        $data['arrAccess'][] = true;
      }
    }

    // Jika ada input yang kosong, return error
    if (in_array(false, $data['arrAccess'])) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Email tidak valid! Silahkan cek dan coba lagi.'],
      ]);
    }

    if ($password !== $repassword) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Konfirmasi kata sandi salah!'],
      ]);
    }

    if (User::where('email', $email)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Email yang anda masukan sudah terdaftar!'],
      ]);
    }

    if (User::where('phone', $phone)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' =>  ['message' => 'Nomor telepon yang anda masukan sudah terdaftar!'],
      ]);
    }
    $return['status'] = true;
    $return['remove_class'][0]['base'] = '#pane_vector';
    $return['remove_class'][0]['class'] = 'd-none';
    $return['add_class'][0]['base'] = '#pane_register';
    $return['add_class'][0]['class'] = 'd-none';
    return response()->json($return);
  }

  public function register(Request $request)
  {
    if (!$request->isMethod('post')) {
      return redirect()->route('home');
    }

    $arrVar = [
      'name' => 'Nama',
      'born_date' => 'Tanggal Lahir',
      'education_status' => 'Status Pendidikan',
      'email' => 'Alamat email',
      'phone' => 'Nomor telepon',
      'gender' => 'Jenis Kelamin',
      'id_vector' => 'Minat',
      'id_riwayat_pelatihan' => 'Riwayat Pelatihan',
      'password' => 'Kata sandi',
      'repassword' => 'Konfirmasi kata sandi',
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi input satu per satu (sesuai dengan logika CI3-mu)
    $optionalFields = ['id_riwayat_pelatihan'];

    foreach ($arrVar as $var => $label) {
      $$var = $request->input($var);

      if (!$$var && !in_array($var, $optionalFields)) {
        return response()->json([
          'status' => 500,
          'alert' => ['message' => "$label tidak boleh kosong!"],
        ]);
        $data['arrAccess'][] = false;
      } else {
        if (!in_array($var, ['repassword'])) {
          $post[$var] = trim($$var);
        }

        $data['arrAccess'][] = true;
      }
    }

    // Jika ada input yang kosong, return error
    if (in_array(false, $data['arrAccess'])) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Email tidak valid! Silahkan cek dan coba lagi.'],
      ]);
    }

    if ($password !== $repassword) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Konfirmasi kata sandi salah!'],
      ]);
    }

    if (User::where('email', $email)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Email yang anda masukan sudah terdaftar!'],
      ]);
    }

    if (User::where('phone', $phone)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' =>  ['message' => 'Nomor telepon yang anda masukan sudah terdaftar!'],
      ]);
    }
    $post['id_category'] = $this->knnPredictCategory($post);
    $post['id_riwayat_pelatihan'] = $post['id_riwayat_pelatihan'] !== '' ? (int) $post['id_riwayat_pelatihan'] : null;
    $user = User::create($post);

    if ($user) {

      return response()->json([
        'status' => 200,
        'alert' => ['message' => 'Anda berhasil mendaftar! Silahkan masuk dengan data anda'],
        'redirect' => route('home'),
      ]);
    }

    return response()->json([
      'status' => 700,
      'alert' => ['message' => 'Gagal menambah data! Silahkan cek data atau coba lagi nanti'],
    ]);
  }


  public function logout(Request $request)
  {
    Auth::logout(); // Logout user
    Session::flush(); // Hapus semua session

    return redirect('/home');
  }

  public function getAge($born_date)
  {
    return now()->diffInYears(Carbon::parse($born_date));
  }

 public function knnPredictCategory($userInput, $k = 5)
  {
    // Ambil semua data user lama yang memiliki kategori
    $allUsers = User::whereNotNull('id_category')->where('deleted', 'N')->get();

    // Hitung nilai minimum dan maksimum dari setiap fitur numerik
    $minMax = [
        'umur' => ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN],
        'vector' => ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN],
        'riwayat' => ['min' => PHP_INT_MAX, 'max' => PHP_INT_MIN],
    ];

    foreach ($allUsers as $user) {
        $umur = $this->getAge($user->born_date);
        $vector = (int) $user->id_vector ?? 0;
        $riwayat = (int) $user->id_riwayat_pelatihan ?? 0;

        $minMax['umur']['min'] = min($minMax['umur']['min'], $umur);
        $minMax['umur']['max'] = max($minMax['umur']['max'], $umur);
        $minMax['vector']['min'] = min($minMax['vector']['min'], $vector);
        $minMax['vector']['max'] = max($minMax['vector']['max'], $vector);
        $minMax['riwayat']['min'] = min($minMax['riwayat']['min'], $riwayat);
        $minMax['riwayat']['max'] = max($minMax['riwayat']['max'], $riwayat);
    }

    // Fungsi bantu untuk normalisasi
    $normalize = function ($value, $min, $max) {
        return ($max - $min) == 0 ? 0 : ($value - $min) / ($max - $min);
    };

    // Normalisasi data input baru
    $umurBaru = $this->getAge($userInput['born_date']);
    $umurBaru = $normalize($umurBaru, $minMax['umur']['min'], $minMax['umur']['max']);

    $genderBaru = $userInput['gender'] === 'Laki-laki' ? 1 : 0;

    $eduMap = ['SMA' => 0, 'SMK' => 1, 'Mahasiswa' => 2];
    $eduBaru = $eduMap[$userInput['education_status']] ?? 0;

    $vectorBaru = $normalize((int) $userInput['id_vector'], $minMax['vector']['min'], $minMax['vector']['max']);

    $riwayatBaru = isset($userInput['id_riwayat_pelatihan']) 
        ? $normalize((int) $userInput['id_riwayat_pelatihan'], $minMax['riwayat']['min'], $minMax['riwayat']['max']) 
        : 0;

    // Proses perhitungan jarak
    $distances = [];

    foreach ($allUsers as $oldUser) {
        $umurLama = $normalize($this->getAge($oldUser->born_date), $minMax['umur']['min'], $minMax['umur']['max']);
        $genderLama = $oldUser->gender === 'Laki-laki' ? 1 : 0;
        $eduLama = $eduMap[$oldUser->education_status] ?? 0;
        $vectorLama = $normalize((int) $oldUser->id_vector ?? 0, $minMax['vector']['min'], $minMax['vector']['max']);
        $riwayatLama = $normalize((int) $oldUser->id_riwayat_pelatihan ?? 0, $minMax['riwayat']['min'], $minMax['riwayat']['max']);

        $dist = sqrt(
            pow($umurBaru - $umurLama, 2) +
            pow($genderBaru - $genderLama, 2) +
            pow($eduBaru - $eduLama, 2) +
            pow($vectorBaru - $vectorLama, 2) +
            pow($riwayatBaru - $riwayatLama, 2)
        );

        $distances[] = ['distance' => $dist, 'category' => $oldUser->id_category];
    }

    // Urutkan dari jarak terpendek
    usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

    // Ambil k tetangga terdekat
    $topK = array_slice($distances, 0, $k);

    // Hitung voting mayoritas
    $counts = array_count_values(array_column($topK, 'category'));

    // Kembalikan kategori dengan jumlah terbanyak
    arsort($counts);
    return array_key_first($counts);
  }
}
