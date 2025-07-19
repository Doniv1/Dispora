<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Contact;
use App\Models\Training;
use App\Models\RegisTraining;
use App\Mail\NotifikasiPelatihan;

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
        $cnt_contact = Contact::count(); // ✅ Tambahan ini

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
        $data['cnt_contact'] = $cnt_contact;

        // Approval List
        $cnt_pending_approval = RegisTraining::where('approved', 'P')->count();
        $data['cnt_pending_approval'] = $cnt_pending_approval;

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
    $id = $request->input('id');
    $status = $request->input('status');

    $data = RegisTraining::with('training', 'user')->find($id);

    if ($data) {
        $user = $data->user;
        $training = $data->training;

        if ($status == 'Y') {
            $data->approved = 'Y';
            $data->is_notified = 'N'; // agar user nanti melihat notifikasi
            $data->save();

            // Kirim email diterima
            Mail::to($user->email)->queue(new NotifikasiPelatihan(
                $user->name,
                $training->title,
                'Y'
            ));

            return response()->json(['status' => true, 'message' => 'Peserta Diterima & Email Sedang Dikirim']);

        } elseif ($status == 'N') {
            // Kirim email ditolak
            Mail::to($user->email)->queue(new NotifikasiPelatihan(
                $user->name,
                $training->title,
                'N'
            ));

            $data->delete(); // langsung hapus jika ditolak
            return response()->json(['status' => true, 'message' => 'Peserta Ditolak & Email Sedang Dikirim']);
        }
    }

    return response()->json(['status' => false, 'message' => 'Data tidak ditemukan.']);
}
}
