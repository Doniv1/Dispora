<?php

namespace App\Exports;

use App\Models\RegisTraining;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegisterExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return RegisTraining::with(['user', 'training.category', 'user.userVectors.vector'])
            ->where('id_training', $this->id)
            ->where('approved', 'Y')
            ->get()
            ->map(function ($item, $key) {
                $user = $item->user;
                return [
                    'Nama' => $user->name,
                    'Jenis Kelamin' => $user->gender ?? '-',
                    'Tanggal Lahir' => $user->born_date ? Carbon::parse($user->born_date)->format('d-m-Y') : '-',
                    'Status Pendidikan' => $user->education_status ?? '-',
                    'No HP' => $user->phone ?? '-',
                    'Email' => $user->email,
                    'Nama Program Pelatihan' => optional($item->training)->title ?? '-',
                    'Kategori Pelatihan' => optional(optional($item->training)->category)->name ?? '-',
                    'Minat' => optional($user->vector)->name ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Status Pendidikan',
            'No HP',
            'Email',
            'Nama Program Pelatihan',
            'Kategori Pelatihan',
            'Minat',
        ];
    }
}
