<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


use App\Models\User;
use App\Models\Form;
use App\Models\Contact;
use App\Models\Banner;
use App\Models\Subscribe;
use App\Models\Training;
use App\Models\RegisTraining;
use App\Models\RegisTrainingDetail;


class UserController extends Controller
{
  //

  public function index()
  {
    $data['title'] = 'Home';

    // Ambil semua training default (untuk pelatihan terbaru)
    $allTrainings = Training::with('category')
      ->where('status', 'Y')
      ->get();

    $recommended = [];

    // Cek apakah user login dan memiliki kategori
    if (Auth::check() && Auth::user()->id_category) {
      $user = Auth::user();

      // Ambil training yang sesuai dengan id_category user
      $trainings = Training::with('category')
        ->where('status', 'Y')
        ->where('id_category', $user->id_category)
        ->orderBy('created_at', 'desc')
        ->take(9)
        ->get();

      // Masukkan ke dalam array ['training' => ...] agar Blade tidak error
      $recommended = $trainings->map(function ($item) {
        return ['training' => $item];
      });
    }

    // Ambil 9 pelatihan terbaru dari semua
    $latestTrainings = $allTrainings->sortByDesc('created_at')->take(9);

    // Ambil semua banner aktif
    $banners = Banner::where('status', 'Y')->get();

    $data['banner'] = $banners;
    $data['training'] = $latestTrainings;
    $data['recommended'] = $recommended;

    return view('user.index', $data);
  }
  
  public function training(Request $request)
  {
    // Variabel dasar
    $title = 'Training';
    $limit = 9;
    $offset = $request->get('offset', 1);
    $search = $request->get('search', '');
    $start = ($offset - 1) * $limit;

    // Query awal
    $query = Training::with(['category'])
      ->where('status', 'Y')
      ->where('deleted', 'N')
      ->whereHas('category', function ($q) {
        $q->where('status', 'Y')->where('deleted', 'N');
      });

    // Filter search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('title', 'like', '%' . $search . '%')
          ->orWhereHas('category', function ($qc) use ($search) {
            $qc->where('name', 'like', '%' . $search . '%');
          });
      });
    }

    // Hitung total data
    $jumlah = $query->count();

    // Ambil data dengan paginasi manual
    $result = $query->orderBy('created_at', 'desc')
      ->offset($start)
      ->limit($limit)
      ->get();

    // Data ke view
    return view('user.training', [
      'title' => $title,
      'result' => $result,
      'jumlah' => $jumlah,
      'offset' => $offset,
      'start' => $start,
      'search' => $search,
      'total' => ($jumlah > 0) ? ceil($jumlah / $limit) : 0,
    ]);
  }

  public function mytraining(Request $request)
  {
    $title = 'Training';
    $limit = 9;
    $offset = $request->get('offset', 1);
    $search = $request->get('search', '');
    $start = ($offset - 1) * $limit;
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Query dari regis_trainings, karena 1 training bisa didaftar berkali-kali
    $query = RegisTraining::with(['training.category']) // include training & category
      ->where('id_user', $id_user)
      ->where('deleted', 'N')
      ->whereHas('training', function ($q) use ($search) {
        $q->where('status', 'Y')
          ->where('deleted', 'N')
          ->whereHas('category', function ($qc) {
            $qc->where('status', 'Y')->where('deleted', 'N');
          });

        // Filter search (jika ada)
        if (!empty($search)) {
          $q->where(function ($qs) use ($search) {
            $qs->where('title', 'like', '%' . $search . '%')
              ->orWhereHas('category', function ($qc) use ($search) {
                $qc->where('name', 'like', '%' . $search . '%');
              });
          });
        }
      });

    $jumlah = $query->count();

    $result = $query->orderBy('created_at', 'desc')
      ->offset($start)
      ->limit($limit)
      ->get();

    return view('user.mytraining', [
      'title' => $title,
      'result' => $result,
      'jumlah' => $jumlah,
      'offset' => $offset,
      'start' => $start,
      'search' => $search,
      'total' => ($jumlah > 0) ? ceil($jumlah / $limit) : 0,
    ]);
  }





  public function insert_contact(Request $request)
  {
    $arrVar = [
      'first_name' => 'Nama depan',
      'last_name' => 'Nama belakang',
      'email' => 'Alamat email',
      'message' => 'Pesan',
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi input satu per satu (sesuai dengan logika CI3-mu)
    foreach ($arrVar as $var => $label) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_contact_' . $var, "$label tidak boleh kosong!"];
        $data['arrAccess'][] = false;
      } else {
        if (!in_array($var, ['first_name', 'last_name'])) {
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
        'status' => 700,
        'alert' => ['message' => 'Format alamat email tidak valid!']
      ]);
    }

    // Insert ke database
    $post['name'] = $first_name . ' ' . $last_name;
    $insert = Contact::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Berhasil menambahkan pesan baru!'],
        'reload' => true
      ]);
    }

    return response()->json([
      'status' => false,
      'alert' => ['message' => 'Gagal menambahkan pesan!'],
    ]);
  }

  public function insert_subscribe(Request $request)
  {
    $arrVar = [
      'email' => 'Alamat email'
    ];

    $data = ['required' => [], 'arrAccess' => []];
    $post = [];

    // Validasi input satu per satu (sesuai dengan logika CI3-mu)
    foreach ($arrVar as $var => $label) {
      $$var = $request->input($var);
      if (!$$var) {
        $data['required'][] = ['req_subscribe_' . $var, "$label tidak boleh kosong!"];
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
      return response()->json([
        'status' => 700,
        'alert' => ['message' => 'Format alamat email tidak valid!']
      ]);
    }

    if (Subscribe::where('email', $email)->exists()) {
      return response()->json([
        'status' => 500,
        'alert' => ['message' => 'Email yang anda masukan sudah terdaftar!'],
      ]);
    }
    // Insert ke database
    $insert = Subscribe::create($post);

    if ($insert) {
      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Berhasil berlangganan!'],
        'input' => ['all' => true]
      ]);
    }

    return response()->json([
      'status' => false,
      'alert' => ['message' => 'Gagal berlangganan!'],
    ]);
  }


  public function get_detail_training(Request $request)
  {
    $id = $request->input('id');

    $result = Training::with('category')->where('id_training', $id)->first();

    $data['title'] = '';
    $data['id_training'] = '';
    $data['category'] = '';
    $data['description'] = '';
    if ($result) {
      $data['id_training'] = $result->id_training;
      $data['title'] = $result->title;
      $data['category'] = $result->category->name;
      $data['description'] = $result->description;
    }

    return response()->json($data);
  }


  public function register_training(Request $request)
  {
    $id_training = $request->input('id_training');
    if (!$id_training || !Training::find($id_training)) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'Invalid training data!']
      ]);
    }

    $form = Form::get();
    $fields = [];
    $fileFields = [];
    $postData = [];
    $fileData = [];
    $errors = [];

    foreach ($form as $field) {
      $fieldName = 'field_' . $field->id_form;

      if ($field->type == 4) {
        // FILE
        if (!$request->hasFile($fieldName)) {
          $errors[] = ['req_regis_training_' . $fieldName, $field->field . ' tidak boleh kosong!'];
        } else {
          $file = $request->file($fieldName);
          if ($file->isValid()) {
            $tujuan = public_path('data/form/');
            if (!File::exists($tujuan)) {
              File::makeDirectory($tujuan, 0755, true, true);
            }

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($tujuan, $filename);
            $fileData[$fieldName] = $filename;
          } else {
            $errors[] = ['req_regis_training_' . $fieldName, $field->field . ' tidak valid!'];
          }
        }
      } else {
        // TEXT / INPUT
        $value = trim($request->input($fieldName));
        if ($value === '') {
          $errors[] = ['req_regis_training_' . $fieldName, $field->field . ' tidak boleh kosong!'];
        } else {
          $postData[$fieldName] = $value;
        }
      }
    }

    if (count($errors) > 0) {
      return response()->json([
        'status' => false,
        'required' => $errors
      ]);
    }

    // Ambil ID user dari session
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');
    if (!$id_user) {
      return response()->json([
        'status' => false,
        'alert' => ['message' => 'User tidak ditemukan, silakan login ulang.']
      ]);
    }

    // Simpan data utama
    $insert = RegisTraining::create([
      'id_user' => $id_user,
      'id_training' => $id_training
    ]);

    if ($insert) {
      $detailData = [];
      foreach ($form as $field) {
        $fieldName = 'field_' . $field->id_form;
        $detailData[] = [
          'id_regis_training' => $insert->id_regis_training,
          'id_form' => $field->id_form,
          'value' => $postData[$fieldName] ?? $fileData[$fieldName] ?? ''
        ];
      }

      RegisTrainingDetail::insert($detailData);

      return response()->json([
        'status' => true,
        'alert' => ['message' => 'Berhasil mendaftar pelatihan!'],
        'reload' => true
      ]);
    }

    return response()->json([
      'status' => false,
      'alert' => ['message' => 'Gagal mendaftar pelatihan!'],
    ]);
  }
}
