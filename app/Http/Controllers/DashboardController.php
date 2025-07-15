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
}
