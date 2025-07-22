<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\UserVector;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Mail\SendOtpMail;
use App\Mail\ResetPasswordLink;

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

    if (strlen($password) < 8) {
    return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Kata sandi minimal 8 karakter!'],
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

    if (strlen($password) < 8) {
    return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Kata sandi minimal 8 karakter!'],
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
    $allUsers = User::whereNotNull('id_category')->where('deleted', 'N')->get();

    if ($allUsers->count() == 0) {
        return null;
    }

    // Map pendidikan
    $eduMap = ['SMA' => 0, 'SMK' => 1, 'Mahasiswa' => 2];

    // Konversi input baru
    $umurBaru = $this->getAge($userInput['born_date']);
    $genderBaru = $userInput['gender'] === 'Laki-laki' ? 1 : 0;
    $eduBaru = $eduMap[$userInput['education_status']] ?? 0;
    $vectorBaru = (int) $userInput['id_vector'];
    $riwayatBaru = isset($userInput['id_riwayat_pelatihan']) ? (int) $userInput['id_riwayat_pelatihan'] : 0;

    // Kumpulkan semua nilai dari dataset untuk normalisasi
    $umurList = [];
    $genderList = [];
    $eduList = [];
    $vectorList = [];
    $riwayatList = [];

    foreach ($allUsers as $user) {
        $umurList[] = $this->getAge($user->born_date);
        $genderList[] = $user->gender === 'Laki-laki' ? 1 : 0;
        $eduList[] = $eduMap[$user->education_status] ?? 0;
        $vectorList[] = (int) $user->id_vector ?? 0;
        $riwayatList[] = (int) $user->id_riwayat_pelatihan ?? 0;
    }

    // Fungsi normalisasi Min-Max
    $minMaxNorm = function ($value, $min, $max) {
        return ($max - $min) == 0 ? 0 : ($value - $min) / ($max - $min);
    };

    // Normalisasi input baru
    $umurNormBaru = $minMaxNorm($umurBaru, min($umurList), max($umurList));
    $genderNormBaru = $minMaxNorm($genderBaru, min($genderList), max($genderList));
    $eduNormBaru = $minMaxNorm($eduBaru, min($eduList), max($eduList));
    $vectorNormBaru = $minMaxNorm($vectorBaru, min($vectorList), max($vectorList));
    $riwayatNormBaru = $minMaxNorm($riwayatBaru, min($riwayatList), max($riwayatList));

    // Hitung jarak setiap data dalam dataset
    $distances = [];

    foreach ($allUsers as $index => $user) {
        $umurLama = $minMaxNorm($umurList[$index], min($umurList), max($umurList));
        $genderLama = $minMaxNorm($genderList[$index], min($genderList), max($genderList));
        $eduLama = $minMaxNorm($eduList[$index], min($eduList), max($eduList));
        $vectorLama = $minMaxNorm($vectorList[$index], min($vectorList), max($vectorList));
        $riwayatLama = $minMaxNorm($riwayatList[$index], min($riwayatList), max($riwayatList));

        $dist = sqrt(
            pow($umurNormBaru - $umurLama, 2) +
            pow($genderNormBaru - $genderLama, 2) +
            pow($eduNormBaru - $eduLama, 2) +
            pow($vectorNormBaru - $vectorLama, 2) +
            pow($riwayatNormBaru - $riwayatLama, 2)
        );

        $distances[] = ['distance' => $dist, 'category' => $user->id_category];
    }

    // Urutkan berdasarkan jarak terpendek
    usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

    // Ambil k tetangga terdekat
    $topK = array_slice($distances, 0, $k);

    // Hitung jumlah kategori
    $counts = array_count_values(array_column($topK, 'category'));

    // Kembalikan kategori terbanyak
    arsort($counts);
    return array_key_first($counts);
  }

public function sendResetLinkEmail(Request $request)
{
  if (!$request->isMethod('post')) {
    return redirect()->route('home');
  }

  $arrVar = [
    'email' => 'Alamat email'
  ];

  $data = ['required' => [], 'arrAccess' => []];
  $post = [];

  foreach ($arrVar as $var => $label) {
    $$var = $request->input($var);
    if (!$$var) {
      $data['required'][] = ['req_reset_' . $var, "$label tidak boleh kosong!"];
      $data['arrAccess'][] = false;
    } else {
      $post[$var] = trim($$var);
      $data['arrAccess'][] = true;
    }
  }

  if (in_array(false, $data['arrAccess'])) {
    return response()->json(['status' => false, 'required' => $data['required']]);
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return response()->json([
      'status' => 500,
      'alert' => ['message' => 'Email tidak valid! Masukkan email yang benar']
    ]);
  }

  // Cek user berdasarkan email
  $user = \App\Models\User::where('email', $email)->where('deleted', 'N')->first();

  if (!$user) {
    return response()->json([
      'status' => 500,
      'alert' => ['message' => 'Email tidak ditemukan dalam sistem!']
    ]);
  }

  if ($user->status == 'N') {
    $reason = $user->reason ? ' dengan alasan </br></br><b>"' . $user->reason . '"</b></br></br>' : '!';
    return response()->json([
      'status' => 700,
      'alert' => ['message' => 'Akun Anda telah diblokir' . $reason . ' Silakan hubungi admin untuk informasi lebih lanjut.']
    ]);
  }

  // Generate token dan simpan ke DB
  $token = Str::random(64);
  DB::table('password_resets')->updateOrInsert(
    ['email' => $email],
    [
      'token' => $token,
      'created_at' => Carbon::now()
    ]
  );

  // Kirim email reset via queue
  Mail::to($email)->queue(new ResetPasswordLink($user->name, $email, $token));

  return response()->json([
    'status' => 200,
    'alert' => ['message' => 'Link reset password telah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.']
  ]);
}

public function showResetForm(Request $request, $token)
{
    $email = $request->query('email');

    $check = DB::table('password_resets')
        ->where('email', $email)
        ->where('token', $token)
        ->first();

    if (!$check) {
        return redirect()->route('home')->with('error', 'Token tidak valid atau sudah kadaluarsa.');
    }

    return view('auth.reset-password', compact('token', 'email'));
}

public function resetPassword(Request $request)
{
    $arrVar = [
        'token' => 'Token',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Konfirmasi Password'
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi manual (agar seragam dengan sendResetLinkEmail)
    foreach ($arrVar as $var => $label) {
        $$var = $request->input($var);
        if (!$$var) {
            $data['required'][] = ['req_reset_' . $var, "$label tidak boleh kosong!"];
            $data['arrAccess'][] = false;
        } else {
            $post[$var] = trim($$var);
            $data['arrAccess'][] = true;
        }
    }

    if (in_array(false, $data['arrAccess'])) {
        return response()->json(['status' => false, 'required' => $data['required']]);
    }

    // Validasi email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json([
            'status' => 500,
            'alert' => ['message' => 'Format email tidak valid!']
        ]);
    }

    // Validasi password min & konfirmasi
    if (strlen($password) < 8) {
        return response()->json([
            'status' => 500,
            'alert' => ['message' => 'Password minimal 8 karakter!']
        ]);
    }

    if ($password !== $password_confirmation) {
        return response()->json([
            'status' => 500,
            'alert' => ['message' => 'Konfirmasi password tidak cocok!']
        ]);
    }

    // Validasi token
    $check = DB::table('password_resets')
        ->where('email', $email)
        ->where('token', $token)
        ->first();

    if (!$check) {
        return response()->json([
            'status' => 500,
            'alert' => ['message' => 'Token tidak valid atau sudah kedaluwarsa!']
        ]);
    }

    // Ambil user aktif
    $user = User::where('email', $email)->where('deleted', 'N')->first();

    if (!$user) {
        return response()->json([
            'status' => 500,
            'alert' => ['message' => 'User tidak ditemukan dalam sistem!']
        ]);
    }

    // Simpan password baru
    $user->password = $password;
    $user->updated_at = now();

    if ($user->save()) {
        DB::table('password_resets')->where('email', $email)->delete();
        Auth::login($user); // login otomatis

        return response()->json([
            'status' => true,
            'alert' => ['message' => 'Password berhasil diubah! Anda akan diarahkan ke beranda.'],
            'redirect' => '/home'
        ]);
    }

    return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi nanti.']
    ]);
}





}