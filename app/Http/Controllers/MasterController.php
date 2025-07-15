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
    if ($role == 1) {
      $page = "admin";
    }

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
        'alert' => ['message' => 'updated successfully!'],
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
        'alert' => ['message' => 'updated successfully!'],
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
        'alert' => ['message' => 'updated successfully!'],
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
        'alert' => ['message' => 'updated successfully!'],
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
      return response()->json(['status' => true, 'message' => 'Data deleted successfully', 'count' => $final]);
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
        'alert' => ['message' => 'updated successfully!'],
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
                'alert' => ['message' => 'updated successfully!'],
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
