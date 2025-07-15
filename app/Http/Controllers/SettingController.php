<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Exports\DynamicExport;
// use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Setting;
use App\Models\WebPhone;
use App\Models\WebEmail;
use App\Models\Sosmed;
use App\Models\SosmedSetting;
use App\Models\Form;
use App\Models\RegisTrainingDetail;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', '');

        $data = [];

        // GLBL
        $data['title'] = 'Setting';
        $data['subtitle'] = 'Setting Lanjutan Website';

        // GET DATA
        $setting = Setting::find(1); // get_single
        $phone = WebPhone::where('id_setting', 1)->get(); // get_all
        $email = WebEmail::where('id_setting', 1)->get();

        // Get all sosmed + ambil url & name_sosmed dari tabel pivot sosmed_setting
        $sosmed = Sosmed::select([
            'sosmeds.*',
            DB::raw("(SELECT url FROM sosmed_setting WHERE sosmed_setting.id_sosmed = sosmeds.id_sosmed AND sosmed_setting.id_setting = 1 LIMIT 1) as url"),
            DB::raw("(SELECT name FROM sosmed_setting WHERE sosmed_setting.id_sosmed = sosmeds.id_sosmed AND sosmed_setting.id_setting = 1 LIMIT 1) as name_sosmed"),
        ])->get();


        // Bisa juga dengan manual select seperti di CI3, pakai DB::select
        // tapi lebih proper kita bikin relasinya (lihat catatan di bawah)

        // SET DATA
        $data['result'] = $setting;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['sosmed'] = $sosmed;
        $data['page'] = $page;

        // DISPLAY
        return view('admin.setting.index', $data);
    }


    // FUNCTION

    public function updateLogo(Request $request)
    {
        $setting = Setting::find(1);
        $tujuan = public_path('data/setting');
        if (!file_exists($tujuan)) {
            mkdir($tujuan, 0755, true);
        }

        $arrAccess = [];

        // Validasi minimal salah satu file/logo tersedia
        $name_icon = $request->input('name_icon', '');
        $name_icon_white = $request->input('name_icon_white', '');
        $name_logo = $request->input('name_logo', '');
        $name_logo_white = $request->input('name_logo_white', '');

        $arrAccess[] = $request->hasFile('logo') || $name_logo || $setting->logo;
        $arrAccess[] = $request->hasFile('logo_white') || $name_logo_white || $setting->logo_white;
        $arrAccess[] = $request->hasFile('icon') || $name_icon || $setting->icon;
        $arrAccess[] = $request->hasFile('icon_white') || $name_icon_white || $setting->icon_white;

        if (in_array(true, $arrAccess)) {
            $post = [];

            // LOGO
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $nama = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan, $nama);
                $post['logo'] = $nama;
                if ($name_logo && file_exists($tujuan . '/' . $name_logo)) {
                    unlink($tujuan . '/' . $name_logo);
                }
            } elseif (!$name_logo && $setting->logo && file_exists($tujuan . '/' . $setting->logo)) {
                unlink($tujuan . '/' . $setting->logo);
                $post['logo'] = '';
            }

            // LOGO WHITE
            if ($request->hasFile('logo_white')) {
                $file = $request->file('logo_white');
                $nama = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan, $nama);
                $post['logo_white'] = $nama;
                if ($name_logo_white && file_exists($tujuan . '/' . $name_logo_white)) {
                    unlink($tujuan . '/' . $name_logo_white);
                }
            } elseif (!$name_logo_white && $setting->logo_white && file_exists($tujuan . '/' . $setting->logo_white)) {
                unlink($tujuan . '/' . $setting->logo_white);
                $post['logo_white'] = '';
            }

            // ICON
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $nama = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan, $nama);
                $post['icon'] = $nama;
                if ($name_icon && file_exists($tujuan . '/' . $name_icon)) {
                    unlink($tujuan . '/' . $name_icon);
                }
            } elseif (!$name_icon && $setting->icon && file_exists($tujuan . '/' . $setting->icon)) {
                unlink($tujuan . '/' . $setting->icon);
                $post['icon'] = '';
            }

            // ICON WHITE
            if ($request->hasFile('icon_white')) {
                $file = $request->file('icon_white');
                $nama = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($tujuan, $nama);
                $post['icon_white'] = $nama;
                if ($name_icon_white && file_exists($tujuan . '/' . $name_icon_white)) {
                    unlink($tujuan . '/' . $name_icon_white);
                }
            } elseif (!$name_icon_white && $setting->icon_white && file_exists($tujuan . '/' . $setting->icon_white)) {
                unlink($tujuan . '/' . $setting->icon_white);
                $post['icon_white'] = '';
            }

            if (count($post) > 0) {
                $updated = $setting->update($post);
                if ($updated) {
                    return response()->json([
                        'status' => true,
                        'alert' => ['message' => 'Data berhasil dirubah'],
                        'reload' => true
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'alert' => ['message' => 'Data gagal dirubah']
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Tidak ada data di rubah']
            ]);
        }

        return response()->json([
            'status' => false,
            'alert' => ['message' => 'Tidak ada data di rubah']
        ]);
    }

    public function updateSeo(Request $request)
    {
        $arrVar = [
            'meta_title' => 'Judul website',
        ];

        $arrAccess = [];
        $post = [];
        $data = [];

        foreach ($arrVar as $var => $label) {
            $value = $request->input($var);
            if (!$value) {
                $data['required'][] = ['req_' . $var, $label . ' tidak boleh kosong!'];
                $arrAccess[] = false;
            } else {
                $post[$var] = trim($value);
                $arrAccess[] = true;
            }
        }

        // META KEYWORD
        // $metaKeywordRaw = $request->input('meta_keyword', '');
        // if ($metaKeywordRaw) {
        //     $decoded = json_decode($metaKeywordRaw, true);
        //     $cleaned = [];
        //     foreach ($decoded as $item) {
        //         $val = str_replace(["'", '"', "`"], "", $item['value']);
        //         $cleaned[] = $val;
        //     }
        //     $post['meta_keyword'] = implode(',', $cleaned);
        // } else {
        //     $post['meta_keyword'] = '';
        // }

        
        $post['meta_address'] = $request->input('meta_address', '');

        // PHONE
        $phones = $request->input('phone', []);
        $namePhones = $request->input('name_phone', []);
        $p = [];

        if ($phones) {
            $no = 0;
            foreach ($phones as $id => $phone) {
                if (!empty($phone)) {
                    $p[$no]['id_setting'] = 1;
                    $p[$no]['phone'] = $phone;
                    $p[$no]['name'] = $namePhones[$id] ?? null;
                    $no++;
                }
            }
        }

        // EMAIL
        $emails = $request->input('email', []);
        $e = [];

        if ($emails) {
            $no = 0;
            foreach ($emails as $id => $email) {
                if (!empty($email)) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return response()->json([
                            'status' => false,
                            'alert' => ['message' => "<b>$email</b> tidak valid!"]
                        ]);
                    }
                    $e[$no]['id_setting'] = 1;
                    $e[$no]['email'] = $email;
                    $no++;
                }
            }
        }

        // CEK SEMUA REQUIRED
        if (!in_array(false, $arrAccess)) {
            $setting = Setting::find(1);
            $update = $setting->update($post);

            if ($update) {
                if (!empty($p)) {
                    WebPhone::where('id_setting', 1)->delete();
                    WebPhone::insert($p);
                }

                if (!empty($e)) {
                    WebEmail::where('id_setting', 1)->delete();
                    WebEmail::insert($e);
                }

                return response()->json([
                    'status' => true,
                    'alert' => ['message' => 'Data berhasil dirubah'],
                    'reload' => true
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'alert' => ['message' => 'Data gagal dirubah']
                ]);
            }
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function updateAbout(Request $request)
    {
        $post = [];
        $setting = Setting::find(1);
        
        $about = $request->input('about', '');
        $cc = ckeditor_check($about);
        if (empty($cc)) {
            $post['about'] = '';
        }else{
            $post['about'] = $about;
        }
        $update = $setting->update($post);
        if ($update) {
            return response()->json([
                'status' => true,
                'alert' => ['message' => 'Data Berhasil Disimpan!'],
                'reload' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'alert' => ['message' => 'Gagal Menyimpan Data']
            ]);
        }
    }

    public function setupSosmed(Request $request)
    {
        $sosmed = $request->input('sosmed', []);
        $name_sosmed = $request->input('name_sosmed', []);

        if (!empty($sosmed)) {
            SosmedSetting::where('id_setting', 1)->delete();

            $data = [];
            foreach ($sosmed as $id => $value) {
                $data[] = [
                    'id_sosmed' => $id,
                    'id_setting' => 1,
                    'name' => $name_sosmed[$id] ?? null,
                    'url' => $value ?? null
                ];
            }

            SosmedSetting::insert($data);
        }

        return response()->json([
            'status' => true,
            'alert' => ['message' => 'Data berhasil dirubah'],
            'reload' => true,
        ]);
    }


    // FUNCTION SOSMED

    public function insert_sosmed(Request $request)
    {
        $arrVar = [
            'icon' => 'Icon',
            'name' => 'Nama'
        ];

        $post = [];
        $arrAccess = [];
        $data = [];

        foreach ($arrVar as $var => $label) {
            $value = $request->input($var);
            if (!$value) {
                $data['required'][] = ['req_sosmed_' . $var, $label . ' tidak boleh kosong!'];
                $arrAccess[] = false;
            } else {
                $post[$var] = trim($value);
                $arrAccess[] = true;
            }
        }

        $url = $request->input('url');

        if (!in_array(false, $arrAccess)) {
            $insert = Sosmed::create($post);
            if ($insert) {
                if ($url) {
                    $cek = SosmedSetting::where('id_setting', 1)
                        ->where('id_sosmed', $insert->id)
                        ->first();

                    if ($cek) {
                        SosmedSetting::where('id_sosmed_setting', $cek->id_sosmed_setting)->delete();
                    }

                    SosmedSetting::create([
                        'id_sosmed' => $insert->id,
                        'url' => $url,
                        'id_setting' => 1
                    ]);
                }

                $data['status'] = true;
                $data['alert']['message'] = 'Berhasil ditambahkan!';
                $data['reload'] = true;
            } else {
                $data['status'] = false;
                $data['alert']['message'] = 'Gagal ditambahkan!';
            }
        } else {
            $data['status'] = false;
        }

        sleep(1.5);
        return response()->json($data);
    }

    public function update_sosmed(Request $request)
    {
        $arrVar = [
            'id_sosmed' => 'ID',
            'icon' => 'Icon',
            'name' => 'Nama'
        ];

        $post = [];
        $arrAccess = [];
        $data = [];

        foreach ($arrVar as $var => $label) {
            $value = $request->input($var);
            if (!$value) {
                $data['required'][] = ['req_sosmed_' . $var, $label . ' tidak boleh kosong!'];
                $arrAccess[] = false;
            } else {
                $post[$var] = trim($value);
                $arrAccess[] = true;
            }
        }

        $url = $request->input('url');
        $id_sosmed = $request->input('id_sosmed');
        $result = Sosmed::find($id_sosmed);

        if (!in_array(false, $arrAccess) && $result) {
            $update = $result->update($post);
            if ($update) {
                if ($url) {
                    $cek = SosmedSetting::where('id_setting', 1)
                        ->where('id_sosmed', $result->id)
                        ->first();

                    if ($cek) {
                        SosmedSetting::where('id_sosmed_setting', $cek->id_sosmed_setting)->delete();
                    }

                    SosmedSetting::create([
                        'id_sosmed' => $result->id,
                        'url' => $url,
                        'id_setting' => 1
                    ]);
                }

                $data['status'] = true;
                $data['alert']['message'] = 'Data berhasil dirubah!';
                $data['reload'] = true;
            } else {
                $data['status'] = false;
                $data['alert']['message'] = 'Data gagal dirubah!';
            }
        } else {
            $data['status'] = false;
        }

        sleep(1.5);
        return response()->json($data);
    }

    // // FORM
   public function insert_form(Request $request)
{
    $field = $request->input('field');
    $type = $request->input('type');

    if (!$field || !$type) {
        return response()->json([
            'status' => false,
            'alert' => 'Field dan Type wajib diisi!',
        ]);
    }

    // Hitung urutan paling besar
    $urutan = Form::where('deleted', 'N')->max('urutan') ?? 0;

    // Tambahkan data baru di paling bawah
    $form = new Form();
    $form->field = $field;
    $form->type = $type;
    $form->urutan = $urutan + 1; // Otomatis nomor berikutnya
    $form->deleted = 'N';
    $form->save();

    return response()->json([
        'status' => true,
        'alert' => 'Formulir berhasil ditambahkan',
        'datatable' => 'table_form',
        'modal' => '#kt_modal_form',
        'input' => '#form_form'
    ]);
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

    public function modal_detail_register(Request $request)
    {
        $id = $request->id;

        // Misal kamu mau dapat banyak data berdasar id_regis_training, bukan id detail tunggal
        $result = RegisTrainingDetail::with('form')->where('id_regis_training', $id)->get();

        $table = '<input type="hidden" id="set_id_regis" value="'.$id.'">';
        $table .= '<table class="table table-bordered">';

        if ($result->count()) {
            foreach ($result as $key) {
                $table .= '<tr>';
                $table .= '<td>' . ($key->form->field ?? '-') . '</td>';

                if (($key->form->type ?? 0) == 4) {
                    $table .= '<td><a href="' . image_check($key->value, 'form') . '" target="_BLANK" class="btn btn-sm btn-info">Cek File</a></td>';
                } else {
                    $table .= '<td>' . $key->value . '</td>';
                }

                $table .= '</tr>';
            }
        } else {
            $table .= '<tr>';
            $table .= '<td colspan="2" class="text-center"><b>TIDAK ADA DATA</b></td>';
            $table .= '</tr>';
        }

        $table .= '</table>';

        return response($table);
    }





    // GLOBAL

    public function switch(Request $request, $db = 'user')
    {
        $id = $request->input('id');
        $action = $request->input('action');
        $primary = $request->input('primary') ?? "id_{$db}";
        $reason = $request->input('reason', '');

        // Check if the table exists in the database
        if (!DB::getSchemaBuilder()->hasTable($db)) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'icon' => 'warning',
                    'message' => 'Table not found!'
                ]
            ]);
        }

        // Check if the data exists in the table
        $res = DB::table($db)->where($primary, $id)->first();

        if (!$res) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'icon' => 'warning',
                    'message' => 'Data not found!'
                ]
            ]);
        }

        $prefix = config('session.prefix');
        $idhps = session($prefix.'_id_user');

        // Update status and reason
        $update = DB::table($db)->where($primary, $id)->update([
            'status' => $action,
            'reason' => $action == 'N' ? $reason : '',
            'blocked_date' => now(),
            'blocked_by' => $idhps
        ]);

        if ($update) {
            $message = $action == 'Y' ? 'Access successfully unlocked!' : 'Access successfully blocked!';
            if ($action == 'N' && $reason != '') {
                $message .= '</br><b>Reason: </b>"' . $reason . '"';
            }

            return response()->json([
                'status' => 200,
                'alert' => [
                    'icon' => 'success',
                    'message' => $message
                ]
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'icon' => 'warning',
                    'message' => $action == 'Y' ? 'Failed to unlock access!' : 'Failed to block access!'
                ]
            ]);
        }
    }


    public function hapusdata(Request $request)
    {
        $id = $request->input('id');
        $db = $request->input('db');
        $primary = $request->input('primary') ?? "id_{$db}";
        $reload = $request->input('reload', '');
        $permanent = $request->input('permanent', 0);

        // Check if the table exists
        if (!DB::getSchemaBuilder()->hasTable($db)) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'message' => 'Table not found!'
                ]
            ]);
        }

        if (!$id || !$db) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'message' => 'Invalid request data!'
                ]
            ]);
        }

        // Check if the data exists
        $res = DB::table($db)->where($primary, $id)->first();

        if (!$res) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'message' => 'Data not found!'
                ]
            ]);
        }

        if ($permanent != 'none') {
            $aksi = DB::table($db)->where($primary, $id)->delete();
        } else {
            $prefix = config('session.prefix');
            $idhps = session($prefix.'_id_user');
            // Soft delete
            $aksi = DB::table($db)->where($primary, $id)->update([
                'deleted' => 'Y',
                'deleted_at' => now(),
                'deleted_by' => $idhps
            ]);
        }

        if ($aksi) {
            return response()->json([
                'status' => 200,
                'alert' => [
                    'message' => 'Data successfully deleted!'
                ]
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'message' => 'Failed to delete data!'
                ]
            ]);
        }
    }



    public function single(Request $request, $db = 'user',$primary = '')
    {
        $id = $request->input('id');
        $primary = $primary ?? "id_{$db}";

        // Cek apakah tabel yang dimaksud ada di database
        if (!DB::getSchemaBuilder()->hasTable($db)) {
            return response()->json([
                'status' => 500,
                'alert' => [
                    'message' => 'Table not found!'
                ]
            ]);
        }

        // Cek apakah data ada di tabel
        $res = DB::table($db)->where($primary, $id)->first();
        if ($res) {
            return response()->json($res);
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
    }

    
}
