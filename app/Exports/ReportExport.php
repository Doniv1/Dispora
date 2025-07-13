<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
     * Ambil data laporan
     */
    public function collection()
    {
        return Report::with(['user', 'category'])->get()->map(function ($item) {
            return [
                'reporter'         => $item->user->name ?? '',
                'case_category'    => $item->category->name ?? '',
                'name_1'           => $item->name_1,
                'name_2'           => $item->name_2,
                'place'            => $item->place,
                'description'      => $item->description,
                'date_on_incident' => date('d-M-Y', strtotime($item->date)),
                'report_date'      => date('d-M-Y H:i', strtotime($item->created_at)),
            ];
        });
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'Reporter',
            'Case Category',
            'Name 1',
            'Name 2',
            'Place',
            'Description',
            'Date on Incident',
            'Report Date'
        ];
    }

    /**
     * Event untuk styling (border)
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Hitung jumlah baris dan kolom
                $rowCount = $sheet->getDelegate()->getHighestRow();
                $colCount = $sheet->getDelegate()->getHighestColumn(); // Misal 'H'

                // Range seluruh data (misal dari A1 sampai H100)
                $cellRange = 'A1:' . $colCount . $rowCount;

                // Tambahkan border
                $sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
