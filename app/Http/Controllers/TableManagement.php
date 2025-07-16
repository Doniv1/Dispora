<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB; // Tambahin ini



use App\Models\User;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Form;
use App\Models\Vector;
use App\Models\Training;
use App\Models\Banner;
use App\Models\RegisTraining;
use Illuminate\Support\Facades\Log;

class TableManagement extends Controller
{



  public function table_user(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
      null,
      'users.name',
      'users.email',
      null,
      'users.status',
    ];

    $query = User::with(['vector', 'category', 'riwayatPelatihan'])->select(
      'users.id_user',
      'users.name',
      'users.email',
      'users.status',
      'users.image',
      'users.phone',             // <— tambahkan
      'users.gender',            // <— tambahkan
      'users.born_date',         // <— tambahkan
      'users.education_status',   // <— tambahkanx 
      'users.id_vector',             // ← pastikan ada
      'users.id_category',           // ← pastikan ada
      'users.id_riwayat_pelatihan'   // ← pastikan ada
    )->where('users.id_user', '!=', $id_user)
      ->where('users.role', 2);
    // Search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('users.name', 'like', "%{$search}%")
          ->orWhere('users.email', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('users.created_at', 'DESC'); // Default sorting
    }

    if ($request->filter_status !== null && $request->filter_status !== '') {
      if ($request->filter_status != 'all') {
        $query->where('status', $request->filter_status);
      }
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    $result = [];
    foreach ($data as $idx => $item) {
      // 1) No
      $no = $start + $idx + 1;

      // 2) User
      $user = '<div class="d-flex align-items-center">'
        . '<a class="symbol symbol-50px"><span class="symbol-label" style="background-image:url('
        . image_check($item->image, 'user', 'user') . ');"></span></a>'
        . '<div class="ms-5"><span class="fs-5 fw-bold text-gray-800">'
        . e($item->name)
        . '</span></div></div>';

      // 3) Kontak
      $kontak = '<div class="d-flex flex-column">'
        . ($item->email  ? '<span class="fs-6"><i class="fa-solid fa-envelope me-2"></i>'
          . e($item->email) . '</span>' : '')
        . ($item->phone  ? '<span class="fs-6"><i class="fa-solid fa-phone me-2"></i>'
          . e($item->phone) . '</span>' : '')
        . ((!$item->email && !$item->phone) ? '<span class="fs-6">-</span>' : '')
        . '</div>';

      // 4) Gender
      $gender = '<span class="fs-7 text-gray-700">'
        . e($item->gender ?? '-')
        . '</span>';

      // 5) Tgl Lahir
      $tglLahir = '<span class="fs-7 text-gray-700">'
        . ($item->born_date
          ? Carbon::parse($item->born_date)->format('d M Y')
          : '-')
        . '</span>';

      // 6) Pendidikan
      $pendidikan = '<span class="fs-7 text-gray-700">'
        . e($item->education_status ?? '-')
        . '</span>';

      // 7) Minat (vector pertama, atau '-' kalau kosong)
      $minat = '<span class="fs-7 text-gray-700">'
        . e($item->vector->name ?? '-')
        . '</span>';

      // 8) Riwayat Pelatihan
      $riwayat = '<span class="fs-7 text-gray-700">'
        . e($item->riwayatPelatihan->title ?? '-')
        . '</span>';

      // 9) Kategori
      $kategori = '<span class="fs-7 text-gray-700">'
        . e($item->category->name ?? '-')
        . '</span>';

      // 10) Status switch
      $checked = $item->status === 'Y' ? 'checked' : '';
      $status = '<div class="form-check form-switch">'
        . '<input onchange="switching(this,event,' . $item->id_user . ')" '
        . 'data-primary="id_user" '
        . 'data-url="' . url('switch/users') . '" '
        . 'class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" '
        . 'id="switch-' . $item->id_user . '" ' . $checked . '>'
        . '</div>';


      // 11) Actions
      $action = '<div class="d-flex justify-content-end gap-1">'
    . '<button class="btn btn-icon btn-warning btn-sm" '
    . 'data-image="' . image_check($item->image, 'user', 'user') . '" '
    . 'onclick="ubah_data(this,' . $item->id_user . ')">'
    . '<i class="ki-outline ki-pencil fs-2"></i></button>'
    
     . '<button type="button" onclick="hapus_data(this,event,' . $item->id_user . ',`users`,`id_user`)" '
    . 'data-datatable="table_user" '
    . 'class="btn btn-icon btn-danger btn-sm" title="Delete">'
    . '<i class="ki-outline ki-trash fs-2"></i></button>'
    . '</div>';

      $result[] = [
        $user,
        $kontak,
        $gender,
        $tglLahir,
        $pendidikan,
        $minat,
        $riwayat,
        $kategori,
        $status,
        $action,
      ];
    }


    $return = [
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result // langsung isi array row di sini
    ];

    return response()->json($return);
  }

  public function table_admin(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
      null,
      'users.name',
      'users.email',
      'users.status',
    ];

    $query = User::select(
      'users.id_user',
      'users.name',
      'users.email',
      'users.status',
      'users.image'
    )->where('users.id_user', '!=', $id_user)
      ->where('users.role', 1);
    // Search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('users.name', 'like', "%{$search}%")
          ->orWhere('users.email', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('users.created_at', 'DESC'); // Default sorting
    }

    if ($request->filter_status !== null && $request->filter_status !== '') {
      if ($request->filter_status != 'all') {
        $query->where('status', $request->filter_status);
      }
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    // Format output
    $result = [];
    foreach ($data as $item) {
      // GET USER
      $user = '';
      $user .= '<div class="d-flex align-items-center">';
      $user .= '<a role="button" class="symbol symbol-50px"><span class="symbol-label" style="background-image:url(' . image_check($item->image, 'user', 'user') . ');"></span></a>';
      $user .= '<div class="ms-5">';
      $user .= '<a role="button" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->name . '</a>';
      $user .= '<span class="text-muted fw-bold text-hover-primary d-block fs-6"><i class="fa-solid fa-envelope" style="margin-right : 5px;"></i>' . $item->email . '</span>';
      $user .= '</div></div>';

      // STATUS
      $checked = '';
      if ($item->status == 'Y') {
        $checked = 'checked';
      }
      $status = '';
      $status .= '<div class="d-flex justify-content-center align-items-center">';
      $status .= '<div class="form-check form-switch"><input onchange="switching(this,event,' . $item->id_user . ')" data-primary="id_user"  data-url="' . url('switch/users') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_user . '" ' . $checked . '></div>';
      $status .= '</div>';

      $kontak = '';
      if ($item->email || $item->phone) {
        $kontak .= '<div class="d-flex justify-content-start flex-column">';
        if ($item->email) {
          $kontak .= '<span class="text-dark fw-bold text-hover-primary d-block fs-6"><i class="fa-solid fa-envelope" style="margin-right : 10px;"></i>' . $item->email . '</span>';
        }
        if ($item->phone) {
          $kontak .= '<span class="text-dark fw-bold text-hover-primary d-block fs-6"><i class="fa-solid fa-phone" style="margin-right : 10px;"></i>' . $item->phone . '</span>';
        }
        $kontak .= '</div>';
      } else {
        $kontak .= '<span class="text-dark fw-bold text-hover-primary d-block fs-6"> - </span>';
      }


      // ACTION
      $action = '';
      $action .= '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_data(this,' . $item->id_user . ')" data-image="' . image_check($item->image, 'user', 'user') . '" data-bs-toggle="modal" data-bs-target="#kt_modal_admin">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </button>
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_user . ',`users`,`id_user`)" data-datatable="table_admin" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';


      $result[] = [
        $user,
        $kontak,
        $status,
        $action
      ];
    }

    $return = [
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result // langsung isi array row di sini
    ];

    return response()->json($return);
  }


  public function table_contact(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
      null,
      'contacts.name',
      'contacts.email',
      'contacts.message',
    ];

    $query = Contact::where('id_contact', '>=', 1);
    // Search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('contacts.name', 'like', "%{$search}%")
          ->orWhere('contacts.email', 'like', "%{$search}%")
          ->orWhere('contacts.message', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('contacts.created_at', 'DESC'); // Default sorting
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    // Format output
    $result = [];
    foreach ($data as $item) {

      $checked = '';
      if ($item->status == 'Y') {
        $checked = 'checked';
      }
      // $status = '';
      // $status .= '<div class="d-flex justify-content-center align-items-center">';
      // $status .= '<div class="form-check form-switch"><input onchange="switching(this,event,' . $item->id_contact . ')" data-primary="id_contact"  data-url="' . url('switch/contacts') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_contact . '" ' . $checked . '></div>';
      // $status .= '</div>';
      // ACTION
      $action = '';
      $action .= '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_contact . ',`contacts`,`id_contact`)" data-datatable="table_contact" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';


      $result[] = [
        $item->name,
        $item->email,
        $item->message,
        // $status,
        $action
      ];
    }

    $return = [
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result // langsung isi array row di sini
    ];

    return response()->json($return);
  }

  public function table_approval(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Mapping kolom untuk DataTables
    $columns = [
      null,
      'regis_trainings.created_at',
      'users.name',
      'trainings.title'
    ];

    // Query dengan join
    $query = RegisTraining::select('regis_trainings.*')
      ->join('users', 'users.id_user', '=', 'regis_trainings.id_user')
      ->join('trainings', 'trainings.id_training', '=', 'regis_trainings.id_training')
      ->where('regis_trainings.approved', 'P')
      ->with(['user', 'training']);

    // Pencarian
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('users.name', 'like', "%{$search}%")
          ->orWhere('users.email', 'like', "%{$search}%")
          ->orWhere('trainings.title', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('regis_trainings.created_at', 'DESC');
    }

    $totalRecords = $query->count();

    $data = $query->skip($start)->take($length)->get();

    // Format output
    $result = [];
    foreach ($data as $item) {
      // USER
      $user = '<div class="d-flex align-items-center">';
      $user .= '<a role="button" class="symbol symbol-50px"><span class="symbol-label" style="background-image:url(' . image_check($item->user->image, 'user', 'user') . ');"></span></a>';
      $user .= '<div class="ms-2 d-flex flex-column">';
      $user .= '<a role="button" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->user->name . '</a>';
      $user .= '<a role="button" class="text-primary fs-7">' . $item->user->email . '</a>';
      $user .= '</div></div>';

      // TRAINING
      $training = '<div class="d-flex align-items-center">';
      $training .= '<a role="button" class="symbol symbol-80px"><span class="symbol-label" style="background-image:url(' . image_check($item->training->image, 'training') . ');"></span></a>';
      $training .= '<div class="ms-5">';
      $training .= '<a role="button" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->training->title . '</a>';
      $training .= '</div></div>';

      // Aksi
      $action = '<div class="d-flex justify-content-end flex-shrink-0">';
      $action .= '<button type="button" onclick="detail_training(' . $item->id_regis_training . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_approval" class="btn btn-icon btn-info btn-sm" title="Aksi">';
      $action .= '<i class="fa-solid fa-gear fs-2"></i>';
      $action .= '</button></div>';

      $result[] = [
        date('d-m-Y H:i', strtotime($item->created_at)),
        $user,
        $training,
        $action
      ];
    }

    return response()->json([
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result
    ]);
  }



  public function table_category(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
      null,
      'categories.name',
      'categories.status',
    ];

    $query = Category::where('deleted', 'N');
    // Search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('categories.name', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('categories.created_at', 'DESC'); // Default sorting
    }

    if ($request->filter_status !== null && $request->filter_status !== '') {
      if ($request->filter_status != 'all') {
        $query->where('status', $request->filter_status);
      }
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    // Format output
    $result = [];
    foreach ($data as $item) {
      // STATUS
      $checked = '';
      if ($item->status == 'Y') {
        $checked = 'checked';
      }
      $status = '';
      $status .= '<div class="d-flex justify-content-center align-items-center">';
      $status .= '<div class="form-check form-switch"><input onchange="switching(this,event,' . $item->id_category . ')" data-primary="id_category"  data-url="' . url('switch/categories') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_category . '" ' . $checked . '></div>';
      $status .= '</div>';


      // ACTION
      $action = '';
      $action .= '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_data(this,' . $item->id_category . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_category">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </button>
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_category . ',`categories`,`id_category`)" data-datatable="table_category" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';


      $result[] = [
        $item->name,
        $status,
        $action
      ];
    }

    $return = [
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result // langsung isi array row di sini
    ];

    return response()->json($return);
  }

  public function table_vector(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $today = Carbon::today()->toDateString();
    $prefix = config('session.prefix');
    $id_user = session($prefix . '_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
      null,
      'vectors.name',
      'vectors.status',
    ];

    $query = Vector::where('deleted', 'N');
    // Search
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('vectors.name', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('vectors.created_at', 'DESC'); // Default sorting
    }

    if ($request->filter_status !== null && $request->filter_status !== '') {
      if ($request->filter_status != 'all') {
        $query->where('status', $request->filter_status);
      }
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    // Format output
    $result = [];
    foreach ($data as $item) {
      // STATUS
      $checked = '';
      if ($item->status == 'Y') {
        $checked = 'checked';
      }
      $status = '';
      $status .= '<div class="d-flex justify-content-center align-items-center">';
      $status .= '<div class="form-check form-switch"><input onchange="switching(this,event,' . $item->id_vector . ')" data-primary="id_vector"  data-url="' . url('switch/vectors') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_vector . '" ' . $checked . '></div>';
      $status .= '</div>';


      // ACTION
      $action = '';
      $action .= '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_data(this,' . $item->id_vector . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_category">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </button>
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_vector . ',`vectors`,`id_vector`)" data-datatable="table_category" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';


      $result[] = [
        $item->name,
        $status,
        $action
      ];
    }

    $return = [
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result // langsung isi array row di sini
    ];

    return response()->json($return);
  }

  public function table_training(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';

    $columns = [
      null,
      null,
      'title',
      'categories.name',           // untuk sorting kategori
      'registrations_count',       // untuk sorting jumlah pendaftar
      null,
      'status',
    ];

    // Query utama: join ke kategori dan hitung jumlah pendaftar
    $query = Training::with(['category'])
      ->withCount('registrations')
      ->join('categories', 'categories.id_category', '=', 'trainings.id_category')
      ->select('trainings.*', 'categories.name as category_name', DB::raw('(select count(*) from regis_trainings where regis_trainings.id_training = trainings.id_training AND regis_trainings.approved = "Y") as registrations_count'));


    // Filter kategori
    if ($request->filter_category && $request->filter_category !== 'all') {
      $query->where('trainings.id_category', $request->filter_category);
    }

    // Filter status
    if ($request->filter_status && $request->filter_status !== 'all') {
      $query->where('trainings.status', $request->filter_status);
    }

    // Pencarian
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('trainings.title', 'like', "%{$search}%")
          ->orWhere('categories.name', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('trainings.created_at', 'desc');
    }

    $totalRecords = $query->count();

    // Ambil data paginasi
    $data = $query->skip($start)->take($length)->get();

    $result = [];
    foreach ($data as $item) {
      // IMAGE
      $image = '<div class="d-flex align-items-center">';
      $image .= '<a role="button" class="symbol symbol-150px"><span class="symbol-label" style="background-image:url(' . image_check($item->image, 'training') . ');"></span></a>';
      $image .= '</div>';

      // VECTOR LIST
      // $list = '';
      // if ($item->trainingVectors->isNotEmpty()) {
      //   $list .= '<div class="d-flex flex-column text-gray-600">';
      //   foreach ($item->trainingVectors as $key) {
      //     $list .= '<div class="d-flex align-items-center py-2"><span class="bullet bg-primary me-3"></span>' . $key->vector->name . '</div>';
      //   }
      //   $list .= '</div>';
      // }

      // STATUS
      $checked = $item->status === 'Y' ? 'checked' : '';
      $status = '<div class="d-flex justify-content-center align-items-center">';
      $status .= '<div class="form-check form-switch">';
      $status .= '<input onchange="switching(this,event,' . $item->id_training . ')" data-primary="id_training" data-url="' . url('switch/trainings') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_training . '" ' . $checked . '>';
      $status .= '</div></div>';

      // ACTION
      $action = '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_data(this,' . $item->id_training . ')" data-image="' . image_check($item->image, 'training') . '" data-bs-toggle="modal" data-bs-target="#kt_modal_training">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </button>
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_training . ',`trainings`,`id_training`)" data-datatable="table_training" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';

      $result[] = [
        $image,
        $item->title,
        '<span class="badge badge-primary">' . $item->category->name . '</span>',
        '<span class="badge badge-info cursor-pointer" data-title="' . $item->title . '" onclick="detail_register(this,' . $item->id_training . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_register">' . $item->registrations_count . ' Peserta' ?? '0 Member' . '</span>',

        $status,
        $action
      ];
    }

    return response()->json([
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result
    ]);
  }



  public function table_banner(Request $request)
  {
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';

    $columns = [
      null,
      null,
      'title',
      'description',
      'status',
    ];

    // Query utama: join ke kategori dan hitung jumlah pendaftar
    $query = Banner::where('id_banner', '>=', 1);

    // Filter status
    if ($request->filter_status && $request->filter_status !== 'all') {
      $query->where('banners.status', $request->filter_status);
    }

    // Pencarian
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('banners.title', 'like', "%{$search}%")
          ->orWhere('banners.description', 'like', "%{$search}%");
      });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
      $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
      $query->orderBy('banners.created_at', 'desc');
    }

    $totalRecords = $query->count();

    // Ambil data paginasi
    $data = $query->skip($start)->take($length)->get();

    $result = [];
    foreach ($data as $item) {
      // IMAGE
      $image = '<div class="d-flex align-items-center">';
      $image .= '<a role="button" class="symbol symbol-150px"><span class="symbol-label" style="background-image:url(' . image_check($item->image, 'banner') . ');"></span></a>';
      $image .= '</div>';

      // STATUS
      $checked = $item->status === 'Y' ? 'checked' : '';
      $status = '<div class="d-flex justify-content-center align-items-center">';
      $status .= '<div class="form-check form-switch">';
      $status .= '<input onchange="switching(this,event,' . $item->id_banner . ')" data-primary="id_banner" data-url="' . url('switch/banners') . '" class="form-check-input cursor-pointer focus-info" type="checkbox" role="switch" id="switch-' . $item->id_banner . '" ' . $checked . '>';
      $status .= '</div></div>';

      // ACTION
      $action = '<div class="d-flex justify-content-end flex-shrink-0">
                            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_data(this,' . $item->id_banner . ')" data-image="' . image_check($item->image, 'banner') . '" data-bs-toggle="modal" data-bs-target="#kt_modal_banner">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </button>
                            <button type="button" onclick="hapus_data(this,event,' . $item->id_banner . ',`banners`,`id_banner`)" data-datatable="table_banner" class="btn btn-icon btn-danger btn-sm" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>';

      $result[] = [
        $image,
        $item->title,
        $item->description,
        $status,
        $action
      ];
    }

    return response()->json([
      'draw' => intval($request->draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $totalRecords,
      'data' => $result
    ]);
  }

  public function table_form(Request $request){
    $search = $request->search['value'] ?? '';
    $start = (int)($request->start ?? 0);
    $length = (int)($request->length ?? 10);
    $orderColumn = $request->order[0]['column'] ?? null;
    $orderDir = $request->order[0]['dir'] ?? 'asc';
    $prefix = config('session.prefix');
    $id_user = session($prefix.'_id_user');

    // Kolom mapping sesuai urutan di frontend DataTables
    $columns = [
        null,               // NO (otomatis)
        'forms.field',      // FIELD
        'forms.type',       // TYPE
    ];

    $query = Form::where('deleted','N');

    // Search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('forms.field', 'like', "%{$search}%");
        });
    }

    // Sorting
    if ($orderColumn !== null && isset($columns[$orderColumn])) {
        $query->orderBy($columns[$orderColumn], $orderDir);
    } else {
        $query->orderBy('forms.created_at', 'desc');
    }

    // Total record
    $totalRecords = $query->count();

    // Pagination
    $query->skip($start)->take($length);
    $data = $query->get();

    // Format output
    $result = [];
    $no = $start + 1;

    foreach ($data as $item) {
        // ACTION
        $action = '<div class="d-flex justify-content-end flex-shrink-0">
            <button type="button" class="btn btn-icon btn-warning btn-sm me-1" title="Update" onclick="ubah_form(this,'.$item->id_form.')" data-bs-toggle="modal" data-bs-target="#kt_modal_form">
                <i class="ki-outline ki-pencil fs-2"></i>
            </button>
            <button type="button" onclick="hapus_data(this,event,'.$item->id_form.',`forms`,`id_form`)" data-datatable="table_form" class="btn btn-icon btn-danger btn-sm" title="Delete">
                <i class="ki-outline ki-trash fs-2"></i>
            </button>
        </div>';

        $result[] = [
            $no++, // urutan otomatis
            $item->field,
            set_form_type($item->type),
            $action
        ];
    }

    $return = [
        'draw' => intval($request->draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords,
        'data' => $result
    ];

    return response()->json($return);
}



  public function table_order_form(Request $request)
  {
    $result = $request->input('result', []);
    if ($result) {
      $set = [];
      $no = 0;
      foreach ($result as $row) {
        $num = $no++;
        $set[$num]['id_form'] = $row['id'];
        $set[$num]['urutan'] = ($row['urutan'] + 1);
      }
      Form::upsert($set, ['id_form'], ['urutan']);
    } else {
      return false;
    }
  }
}
