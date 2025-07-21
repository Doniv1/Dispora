<?php

namespace App\Http\Controllers;

use App\Exports\RegisterExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Category;
use App\Models\Vector;
use App\Models\Training;
use App\Models\TrainingVector;
use App\Models\RegisTraining;
use App\Models\Banner;
use App\Models\Form;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MasterController extends Controller
{

  // GET VIEW
  public function user()
  {
    // SET TITLE
    $data['title'] = 'Master Users';
    $data['subtitle'] = 'Users management';

    return view('admin.master.user', $data);
  }

  public function admin()
  {
    // SET TITLE
    $data['title'] = 'Master Admins';
    $data['subtitle'] = 'Admins Management';

    return view('admin.master.admin', $data);
  }

  public function category()
  {
    // SET TITLE
    $data['title'] = 'Master Kategori';
    $data['subtitle'] = 'Kategori Management';

    return view('admin.master.category', $data);
  }


  public function vector()
  {
    // SET TITLE
    $data['title'] = 'Master Minat';
    $data['subtitle'] = 'Minat Management';

    return view('admin.master.vector', $data);
  }


  public function training()
  {
    // SET TITLE
    $data['title'] = 'Master Training';
    $data['subtitle'] = 'Training Management';

    // GET DATA
    $category = Category::where('status', 'Y')->get();
    $vector = Vector::where('status', 'Y')->get();

    // SET DATA
    $data['category'] = $category;
    $data['vector'] = $vector;

    return view('admin.master.training', $data);
  }

  public function banner()
  {
    // SET TITLE
    $data['title'] = 'Master Banner';
    $data['subtitle'] = 'Banner Management';

    return view('admin.master.banner', $data);
  }

  public function single_training(Request $request)
  {
    $id = $request->input('id');

    $result = Training::find($id);

    $vector = TrainingVector::where('id_training', $id)->get();

    $vt = [];
    if ($vector) {
      foreach ($vector as $key) {
        $vt[] = $key->id_vector;
      }
    }
    $data['result'] = $result;
    $data['vector'] = $vt;
    return response()->json($data);
  }


  // POST FUNCTION

  //ADMIN
  public function insert_admin(Request $request)
  {
    $arrVar = [
        'name' => 'Full name',
        'email' => 'Email address',
        'phone' => 'Phone number',
        'password' => 'Password',
        'repassword' => 'Password confirmation',
        'role' => 'Peran'
    ];

    $post = [];
    $data = [];

    foreach ($arrVar as $var => $label) {
        $$var = $request->input($var);

        if (!$$var) {
            return response()->json([
                'status' => false,
                'alert' => ['message' => "$label tidak boleh kosong!"]
            ]);
        }

        if (!in_array($var, ['repassword'])) {
            $post[$var] = trim($$var);
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Email tidak valid!']
        ]);
    }

    if (User::where('email', $email)->where('deleted', 'N')->exists()) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Email sudah terdaftar!']
        ]);
    }

    if (User::where('phone', $phone)->where('deleted', 'N')->exists()) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Nomor telepon sudah terdaftar!']
        ]);
    }

    if ($password !== $repassword) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Konfirmasi password tidak cocok!']
        ]);
    }

    // Upload gambar jika ada
    $tujuan = public_path('data/user/');
    if (!File::exists($tujuan)) {
        File::makeDirectory($tujuan, 0755, true, true);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($tujuan, $fileName);
        $post['image'] = $fileName;
    }

    $prefix = config('session.prefix');
    $id_user = session($prefix.'_id_user');

    $post['password'] = $password;
    $post['created_by'] = $id_user;

    $insert = User::create($post);

    if ($insert) {
        return response()->json([
            'status' => true,
            'alert' => ['message' => 'Admin berhasil ditambahkan!'],
            'datatable' => 'table_admin',
            'modal' => ['id' => '#kt_modal_admin', 'action' => 'hide'],
            'input' => ['all' => true]
        ]);
    } else {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Gagal menambahkan admin!']
        ]);
    }
  }

  public function update_admin(Request $request)
  {
    if ($id == 1) {
    return response()->json([
        'status' => false,
        'alert' => ['message' => 'Admin utama tidak boleh diubah!']
    ]);
    }

    $id = $request->id_user;
    $user = User::where('id_user', $id)->where('deleted', 'N')->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Admin tidak ditemukan!']
        ]);
    }

    $arrVar = [
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'phone' => 'Nomor Telepon',
        'role' => 'Peran'
    ];

    $post = [];

    foreach ($arrVar as $var => $label) {
        $$var = $request->input($var);

        if (!$$var) {
            return response()->json([
                'status' => false,
                'alert' => ['message' => "$label tidak boleh kosong!"]
            ]);
        }

        $post[$var] = trim($$var);
    }

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Format email tidak valid!']
        ]);
    }

    // Cek duplikasi email dan phone selain dirinya sendiri
    if (User::where('email', $email)->where('id_user', '!=', $id)->where('deleted', 'N')->exists()) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Email sudah digunakan!']
        ]);
    }

    if (User::where('phone', $phone)->where('id_user', '!=', $id)->where('deleted', 'N')->exists()) {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Nomor telepon sudah digunakan!']
        ]);
    }

    // Jika password diisi, validasi
    if ($request->filled('password')) {
        if ($request->password !== $request->repassword) {
            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Konfirmasi password tidak cocok!']
            ]);
        }
        $post['password'] = $request->password;
    }

    // Upload image jika ada
    $tujuan = public_path('data/user/');
    $name_image = $request->name_image;

    if (!File::exists($tujuan)) {
        File::makeDirectory($tujuan, 0755, true, true);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($tujuan, $fileName);

        // Hapus foto lama jika ada
        if ($user->image && file_exists($tujuan . $user->image)) {
            @unlink($tujuan . $user->image);
        }

        $post['image'] = $fileName;
    } elseif (!$name_image) {
        if ($user->image && file_exists($tujuan . $user->image)) {
            @unlink($tujuan . $user->image);
        }
        $post['image'] = null;
    }

    $prefix = config('session.prefix');
    $post['updated_by'] = session($prefix . '_id_user');

    // Proses update
    $update = $user->update($post);

    if ($update) {
        return response()->json([
            'status' => true,
            'alert' => ['message' => 'Admin berhasil diperbarui!'],
            'datatable' => 'table_admin',
            'modal' => ['id' => '#kt_modal_admin', 'action' => 'hide'],
            'input' => ['all' => true]
        ]);
    } else {
        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Gagal memperbarui admin!']
        ]);
    }
  }


 
  // // USER
  public function insert_user(Request $request)
  {
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
      'role' => 'Peran'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

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

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $tujuan = public_path('data/user/');
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      $post['image'] = $fileName;
    }

    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      return response()->json([
        'status' => 700,
        'alert' => ['message' => 'Email tidak valid! Silahkan cek dan coba lagi.']
      ]);
    }

    if (User::where('email', $request->email)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Email yang anda masukan sudah terdaftar!']
      ]);
    }

    if (User::where('phone', $phone)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' =>  ['message' => 'Nomor telepon yang anda masukan sudah terdaftar!'],
      ]);
    }

    if (strlen($password) < 8) {
    return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Kata sandi minimal 8 karakter!'],
    ]);
    }

    if ($request->password !== $request->repassword) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Konfirmasi kata sandi salah!']
      ]);
    }

    $post['id_category'] = $this->knnPredictCategory($post);
    $post['id_riwayat_pelatihan'] = $post['id_riwayat_pelatihan'] !== '' ? (int) $post['id_riwayat_pelatihan'] : null;

    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    $page = 'user';

    $post['password'] = $request->password;
    $post['created_by'] = $id_user;

    $insert = User::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data added successfully!'],
        'datatable' => 'table_' . $page,
        'modal' => ['id' => '#kt_modal_' . $page, 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to add data!']
      ]);
    }
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


  public function update_user(Request $request)
  {
    $id = $request->id_user;
    $user = User::where('id_user', $id)->where('deleted', 'N')->first();

    if (!$user) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'User not found!']
      ]);
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
      'role' => 'Peran'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

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

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }
    // Cek duplikat email (exclude current user)
    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      return response()->json([
        'status' => 700,
        'alert' => ['message' => 'Email tidak valid! Silahkan cek dan coba lagi.']
      ]);
    }

    if (User::where('email', $request->email)->where('id_user', '!=', $id)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Email yang anda masukan sudah terdaftar!']
      ]);
    }

    if (User::where('phone', $request->phone)->where('id_user', '!=', $id)->where('deleted', 'N')->exists()) {
      return response()->json([
        'status' => 500,
        'alert' =>  ['message' => 'Nomor telepon yang anda masukan sudah terdaftar!'],
      ]);
    }

    // Jika password diisi, validasi dan hash
    if ($request->filled('password')) {
      if ($request->password !== $request->repassword) {
        return response()->json([
          'status' => false,
          'alert' => ['message' => 'Konfirmasi kata sandi salah!']
        ]);
      }
      $post['password'] = $request->password;
    }

    $tujuan = public_path('data/user/');
    $name_image = $request->name_image;
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      if ($user->image && file_exists($tujuan . $user->image)) {
        unlink($tujuan . $user->image);
      }

      $post['image'] = $fileName;
    } elseif (!$name_image) {
      if ($user->image && file_exists($tujuan . $user->image)) {
        unlink($tujuan . $user->image);
      }
      $post['image'] = null;
    }

    $post['id_category'] = $this->knnPredictCategory($post);
    $post['id_riwayat_pelatihan'] = $post['id_riwayat_pelatihan'] !== '' ? (int) $post['id_riwayat_pelatihan'] : null;


    $page = 'user';
    if ($role == 1) {
      $page = "admin";
    }

    $update = $user->update($post);

    if ($update) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data Berhasil Diperbarui'],
        'datatable' => 'table_' . $page,
        'modal' => ['id' => '#kt_modal_' . $page, 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to update!']
      ]);
    }


    return response()->json(['status' => false]);
  }


  // // CATEGORY
  public function insert_category(Request $request)
  {
    $arrVar = [
      'name' => 'Category',
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    $post['created_by'] = $id_user;

    $insert = Category::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data added successfully!'],
        'datatable' => 'table_category',
        'modal' => ['id' => '#kt_modal_category', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to add data!']
      ]);
    }
  }

  public function update_category(Request $request)
  {
    $id = $request->id_category;
    $category = Category::where('id_category', $id)->where('deleted', 'N')->first();

    if (!$category) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Category not found!']
      ]);
    }

    $arrVar = [
      'name' => 'Category'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $update = $category->update($post);

    if ($update) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data Berhasil Diperbarui'],
        'datatable' => 'table_category',
        'modal' => ['id' => '#kt_modal_category', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to update!']
      ]);
    }


    return response()->json(['status' => false]);
  }




  // // VECTOR

  public function insert_vector(Request $request)
  {
    $arrVar = [
      'name' => 'Vector',
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    $post['created_by'] = $id_user;

    $insert = Vector::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data added successfully!'],
        'datatable' => 'table_vector',
        'modal' => ['id' => '#kt_modal_vector', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to add data!']
      ]);
    }
  }

  public function update_vector(Request $request)
  {
    $id = $request->id_vector;
    $vector = Vector::where('id_vector', $id)->where('deleted', 'N')->first();

    if (!$vector) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Vector not found!']
      ]);
    }

    $arrVar = [
      'name' => 'Vector'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $update = $vector->update($post);

    if ($update) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data Berhasil Diperbarui'],
        'datatable' => 'table_vector',
        'modal' => ['id' => '#kt_modal_vector', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to update!']
      ]);
    }


    return response()->json(['status' => false]);
  }



  // // TRAINING
  public function insert_training(Request $request)
  {
    $arrVar = [
      'title' => 'Judul',
      'id_category' => 'Kategori',
      'description' => 'Deskripsi',
      'sort_description' => 'Deskripsi singkat'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        if (in_array($var, ['description'])) {
          $cc = ckeditor_check($$var);
          if (empty($cc)) {
            $data['required'][] = ['req_' . $var, $value . " tidak boleh kosong!"];
            $arrAccess[] = false;
          } else {
            $post[$var] = $$var;
            $arrAccess[] = true;
          }
        } else {
          if ($$var === '') {
            $data['required'][] = ['req_' . $var, $value . " tidak boleh kosong!"];
            $arrAccess[] = false;
          } else {
            $post[$var] = $$var;
            $arrAccess[] = true;
          }
        }
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $tujuan = public_path('data/training/');
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      $post['image'] = $fileName;
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Image cannot be null!']
      ]);
    }
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    $post['created_by'] = $id_user;

    $insert = Training::create($post);

    if ($insert) {
      $vector = $request->input('vector');

      if ($vector) {
        $set = [];
        $no = 0;
        foreach ($vector as $key) {
          $num = $no++;
          $set[$num]['id_training'] = $insert->id_training;
          $set[$num]['id_vector'] = $key;
        }
        TrainingVector::insert($set);
      }
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data added successfully!'],
        'datatable' => 'table_training',
        'modal' => ['id' => '#kt_modal_training', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to add data!']
      ]);
    }
  }

  public function update_training(Request $request)
  {
    $id = $request->id_training;
    $training = Training::where('id_training', $id)->where('deleted', 'N')->first();

    if (!$training) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'training not found!']
      ]);
    }

    $arrVar = [
      'title' => 'Judul',
      'id_category' => 'Kategori',
      'description' => 'Deskripsi',
      'sort_description' => 'Deskripsi singkat'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $tujuan = public_path('data/training/');
    $name_image = $request->name_image;
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      if ($training->image && file_exists($tujuan . $training->image)) {
        unlink($tujuan . $training->image);
      }

      $post['image'] = $fileName;
    } elseif (!$name_image) {
      if ($training->image && file_exists($tujuan . $training->image)) {
        return response()->json([
          'status' => false,
          'alert' => ['message' => 'Image cannot be null!']
        ]);
      }
    }


    $update = $training->update($post);

    if ($update) {
      $vector = $request->input('vector');
      TrainingVector::where('id_training', $id)->delete();
      if ($vector) {
        $set = [];
        $no = 0;
        foreach ($vector as $key) {
          $num = $no++;
          $set[$num]['id_training'] = $id;
          $set[$num]['id_vector'] = $key;
        }
        TrainingVector::insert($set);
      }
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data Berhasil Diperbarui'],
        'datatable' => 'table_training',
        'modal' => ['id' => '#kt_modal_training', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to update!']
      ]);
    }


    return response()->json(['status' => false]);
  }

  public function modal_register(Request $request)
  {
    $id = $request->id;
    $result = RegisTraining::with(['user'])->where('id_training', $id)->where('approved', 'Y')->get();
    $data['result'] = $result;

    return view('admin.master.modal.register', $data);
  }

  public function download_register(Request $request)
  {
    $id = $request->id;
    $training = Training::where('id_training', $id)->firstOrFail();
    $filename = 'Daftar_Peserta_' . str_replace(' ', '_', $training->title) . '.xlsx';

    return Excel::download(new RegisterExport($id), $filename);
  }

  public function delete_regis(Request $request)
  {
    $id = $request->id;

    if (!$id) {
      return response()->json(['status' => false, 'message' => 'ID not found']);
    }

    $cek = RegisTraining::find($id);

    if (!$cek) {
      return response()->json(['status' => false, 'message' => 'Data not found']);
    }

    try {
      $count = RegisTraining::where('id_training', $cek->id_training)->where('approved', 'Y')->count();
      $cek->delete();
      $final = $count - 1;
      return response()->json(['status' => true, 'message' => 'Data Berhasil Dihapus', 'count' => $final]);
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message' => 'Failed to delete data']);
    }
  }



  // // BANNER
  public function insert_banner(Request $request)
  {
    $arrVar = [
      'title' => 'Judul',
      'description' => 'Deskripsi'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        if (in_array($var, ['description'])) {
          $cc = ckeditor_check($$var);
          if (empty($cc)) {
            $data['required'][] = ['req_' . $var, $value . " tidak boleh kosong!"];
            $arrAccess[] = false;
          } else {
            $post[$var] = $$var;
            $arrAccess[] = true;
          }
        } else {
          if ($$var === '') {
            $data['required'][] = ['req_' . $var, $value . " tidak boleh kosong!"];
            $arrAccess[] = false;
          } else {
            $post[$var] = $$var;
            $arrAccess[] = true;
          }
        }
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $tujuan = public_path('data/banner/');
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      $post['image'] = $fileName;
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Image cannot be null!']
      ]);
    }
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    $post['created_by'] = $id_user;

    $insert = Banner::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data added successfully!'],
        'datatable' => 'table_banner',
        'modal' => ['id' => '#kt_modal_banner', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to add data!']
      ]);
    }
  }

  public function update_banner(Request $request)
  {
    $id = $request->id_banner;
    $banner = Banner::where('id_banner', $id)->where('deleted', 'N')->first();

    if (!$banner) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'banner not found!']
      ]);
    }

    $arrVar = [
      'title' => 'Judul',
      'description' => 'Deskripsi'
    ];

    $post = [];
    $arrAccess = [];
    $data = [];

    foreach ($arrVar as $var => $value) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
        $arrAccess[] = false;
      } else {
        $post[$var] = trim($$var);
        $arrAccess[] = true;
      }
    }

    if (in_array(false, $arrAccess)) {
      return response()->json(['status' => false, 'required' => $data['required']]);
    }

    $tujuan = public_path('data/banner/');
    $name_image = $request->name_image;
    if (!File::exists($tujuan)) {
      File::makeDirectory($tujuan, 0755, true, true);
    }
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
      $image->move($tujuan, $fileName);

      if ($banner->image && file_exists($tujuan . $banner->image)) {
        unlink($tujuan . $banner->image);
      }

      $post['image'] = $fileName;
    } elseif (!$name_image) {
      if ($banner->image && file_exists($tujuan . $banner->image)) {
        return response()->json([
          'status' => false,
          'alert' => ['message' => 'Image cannot be null!']
        ]);
      }
    }


    $update = $banner->update($post);

    if ($update) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Data Berhasil Diperbarui'],
        'datatable' => 'table_banner',
        'modal' => ['id' => '#kt_modal_banner', 'action' => 'hide'],
        'input' => ['all' => true]
      ]);
    } else {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Failed to update!']
      ]);
    }


    return response()->json(['status' => false]);
  }

  // // FORM
    public function insert_form(Request $request)
    {
        $arrVar = [
            'field' => 'Field',
            'type' => 'Type'
        ];

        $post = [];
        $arrAccess = [];
        $data = [];

        foreach ($arrVar as $var => $value) {
            $$var = $request->input($var);
            if (!$$var) {
                $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
                $arrAccess[] = false;
            } else {
                $post[$var] = trim($$var);
                $arrAccess[] = true;
            }
        }

        if (in_array(false, $arrAccess)) {
            return response()->json(['status' => false, 'required' => $data['required']]);
        }

        $insert = Form::create($post);

        if ($insert) {
            return response()->json([
                'status' => true,
                'alert' => ['message' => 'Data Berhasil Ditambahkan'],
                'datatable' => 'table_form',
                'modal' => ['id' => '#kt_modal_form', 'action' => 'hide'],
                'input' => ['all' => true]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Failed to add data!']
            ]);
        }
    }

    public function update_form(Request $request)
    {
        $id = $request->id_form;
        $form = Form::where('id_form', $id)->where('deleted', 'N')->first();

        if (!$form) {
            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Form not found!']
            ]);
        }

        $arrVar = [
            'field' => 'Field',
            'type' => 'Type'
        ];

        $post = [];
        $arrAccess = [];
        $data = [];

        foreach ($arrVar as $var => $value) {
            $$var = $request->input($var);
            if (!$$var) {
                $data['required'][] = ['req_' . $var, "$value cannot be empty!"];
                $arrAccess[] = false;
            } else {
                $post[$var] = trim($$var);
                $arrAccess[] = true;
            }
        }

        if (in_array(false, $arrAccess)) {
            return response()->json(['status' => false, 'required' => $data['required']]);
        }

        $update = $form->update($post);

        if ($update) {
            return response()->json([
                'status' => true,
                'alert' => ['message' => 'Data Berhasil Diperbarui'],
                'datatable' => 'table_form',
                'modal' => ['id' => '#kt_modal_form', 'action' => 'hide'],
                'input' => ['all' => true]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Failed to update!']
            ]);
        }
        

        return response()->json(['status' => false]);
    }

}
