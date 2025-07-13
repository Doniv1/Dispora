<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\Contact;
use App\Models\Training;
use App\Models\RegisTraining;

class DashboardController extends Controller
{
    
    public function index()
    {
        $prefix = config('session.prefix');
        $role = session($prefix . '_role');
        $id_user = session($prefix . '_id_user');

        // Cek session user
        if (!$role || !$id_user) {
            return redirect()->route('login');
        }

        $data['title'] = 'Dashboard';
        $data['subtitle'] = 'Admin Landing Page';

        // Ambil semua training yang aktif dan tidak dihapus
        $training = Training::with('registrations')
            ->where('status', 'Y')
            ->where('deleted', 'N')
            ->get();

        $cnt_training = $training->count();

        // Hitung admin dan user dari data user aktif dan tidak dihapus
        $cnt_admin = User::where('status', 'Y')
            ->where('deleted', 'N')
            ->where('role', 1)
            ->count();

        $cnt_user = User::where('status', 'Y')
            ->where('deleted', 'N')
            ->where('role', '!=', 1)
            ->count();

        // Siapkan data grafik: jumlah pendaftar per training
        $grafik = [];
        if ($training) {
            foreach ($training as $row) {
                $grafik[] = [
                    'training' => $row->title,
                    'value' => $row->registrations->count()
                ];
            }

        }
        
        // Kirim data ke view
        $data['grafik'] = json_encode($grafik);
        $data['cnt_training'] = $cnt_training;
        $data['cnt_admin'] = $cnt_admin;
        $data['cnt_user'] = $cnt_user;

        return view('admin.dashboard.index', $data);
    }





    public function contact()
    {
        // SET TITLE
        $data['title'] = 'Contact List';
        $data['subtitle'] = 'Contact Management';

        return view('admin.dashboard.contact',$data);
    }


    public function approval()
    {
        // SET TITLE
        $data['title'] = 'Approval List';
        $data['subtitle'] = 'Approval Management';

        return view('admin.dashboard.approval',$data);
    }


    public function report()
    {
        // SET TITLE
        $data['title'] = 'Report List';
        $data['subtitle'] = 'Report Management';

        return view('admin.dashboard.report',$data);
    }

    public function profile()
    {
        // PARAMETER
        $prefix = config('session.prefix');
        $id_user = session($prefix.'_id_user');

        // SET TITLE
        $data['title'] = 'Profile';
        $data['subtitle'] = 'Personal biodata management';

        // GET DATA
        $result = User::where('id_user', $id_user)->where('deleted','N')->first();

        // SET DATA
        $data['result'] = $result;
        return view('admin.dashboard.profile',$data);
    }

    public function updateProfile(Request $request)
    {
        $arrVar = [
            'name' => 'Full name'
        ];

        $post = [];
        $arrAccess = [];
        $data = ['required' => []];

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

        // Jika ada input yang kosong, return error
        if (in_array(false, $arrAccess)) {
            return response()->json(['status' => false, 'required' => $data['required']]);
        }

        $prefix = config('session.prefix');
        $id_user = session($prefix.'_id_user');
        $name_image = $request->name_image;
        $result = User::where('id_user', $id_user)->first();

        if (!in_array(false, $arrAccess)) {
            $tujuan = public_path('data/user/');
            if (!File::exists($tujuan)) {
                File::makeDirectory($tujuan, 0755, true, true);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($tujuan, $fileName);
                
                if ($result->image && file_exists($tujuan . $result->image)) {
                    unlink($tujuan . $result->image);
                }
                
                $post['image'] = $fileName;
                session([
                    "{$prefix}_image"  => $fileName
                ]);
            } elseif (!$name_image) {
                if ($result->image && file_exists($tujuan . $result->image)) {
                    unlink($tujuan . $result->image);
                }
                $post['image'] = null;
            }

            $update = $result->update($post);
            if ($update) {
                session([
                    "{$prefix}_name"  => $post['name']
                ]);
                return response()->json(['status' => true, 'alert' => ['message' => 'profile changed successfully'], 'reload' => true]);
            } else {
                return response()->json(['status' => false, 'alert' => ['message' => 'profile failed to change']]);
            }
        }

        return response()->json(['status' => false]);
    }

    public function updateEmail(Request $request)
    {
        // Ambil data dari input
        $email = strtolower($request->email);
        $password = $request->password;
        $prefix = config('session.prefix');
        $id_user = Session::get("{$prefix}_id_user");

        // Validasi input
        if (!$email || !$password) {
            return response()->json(['status' => 700, 'message' => 'No data detected! Please enter data']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 700, 'message' => 'Invalid email! Please enter a valid email']);
        }

        // Cek user berdasarkan email
        $mail = User::where('email', $email)->where('id_user','!=',$id_user)->where('deleted','N')->first();
        $user = User::where('id_user',$id_user)->first();
        if ($email == $user->email) {
            return response()->json(['status' => 700, 'message' => 'No email changes detected']);
        }

        if (!$mail) {

            // Cek password
            if (Hash::check($password, $user->password)) {
                session([
                    "{$prefix}_email"  => $email
                ]);
                $user->email = $email;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'You have successfully changed your email!'
                ]);
            } else {
                return response()->json(['status' => 500, 'message' => 'Incorrect password! Please enter the correct password.']);
            }
        } else {
            return response()->json(['status' => 500, 'message' => 'Email already registered in the system!']);
        }
    }

    public function set_approval(Request $request)
    {
        $status = $request->input('status','Y');
        $id = $request->input('id');
        $result = RegisTraining::find($id);
        if (!$result) {
            $data['status'] = false;
            $data['message'] = 'Cannot find the data';
            echo json_encode($data);
            exit;
        }
        if ($status == 'Y') {
            $post['approved'] = $status;
            $update = $result->update($post);
            $data['status'] = true;
            $data['message'] = 'Register approved';
            echo json_encode($data);
            exit;
        }else{
            $result->delete();
            $data['status'] = true;
            $data['message'] = 'Register unapproved';
            echo json_encode($data);
            exit;
        }
    }
    public function updatePassword(Request $request)
    {
        // Ambil data dari input
        $currentpassword = $request->currentpassword;
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;
        $prefix = config('session.prefix');
        $id_user = Session::get("{$prefix}_id_user");

        // Validasi input
        if (!$confirmpassword || !$newpassword || !$currentpassword) {
            return response()->json(['status' => 700, 'message' => 'No data detected! Please enter data']);
        }


        $user = User::where('id_user',$id_user)->first();
        // Cek password
        if (Hash::check($currentpassword, $user->password)) {
            if ($newpassword === $confirmpassword) {
                $user->password = $newpassword;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'You have successfully changed your password!'
                ]);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'new password confirmation does not match'
                ]);
            }
            
        } else {
            return response()->json(['status' => 500, 'message' => 'Incorrect password! Please enter the correct password.']);
        }
    }

    public function accountDeactivated(Request $request)
    {
        $prefix = config('session.prefix');
        $id_user = Session::get("{$prefix}_id_user");

        $user = User::where('id_user',$id_user)->first();
        $user->status = 'N';
        $user->reason = 'you have deactivated your account';
        $user->blocked_date = now();
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => 'Your account has been deactivated.',
            'redirect' => route('logout')
        ]);
    }
}
