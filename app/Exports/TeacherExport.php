<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Teacher::with(['user', 'schedules.subject', 'activeHomeroom.classroom'])
            ->orderBy('nip')
            ->get();
    }

    public function map($teacher): array
    {
        // Get unique subjects taught by this teacher
        $subjects = $teacher->schedules->pluck('subject.name')->unique()->filter()->implode(', ') ?: 'Belum ada jadwal';

        return [
            "'" . ($teacher->nip ?? '-'), // Prefix with ' to force text format in Excel
            $teacher->user->name ?? '-',
            $teacher->user->email ?? '-',
            $teacher->gender?->label() ?? 'Belum diisi',
            $teacher->date_of_birth?->format('d/m/Y') ?? 'Belum diisi',
            $subjects,
            $teacher->activeHomeroom?->classroom->name ?? 'Bukan wali kelas',
            $teacher->address ?? 'Belum diisi',
        ];
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Guru',
            'Email',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Mata Pelajaran',
            'Wali Kelas',
            'Alamat',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 30,
            'C' => 25,
            'D' => 15,
            'E' => 18,
            'F' => 25,
            'G' => 15,
            'H' => 40,
        ];
    }

    public function title(): string
    {
        return 'Data Guru';
    }
}
