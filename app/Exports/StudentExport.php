<?php

namespace App\Exports;

use App\Models\Student;
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

class StudentExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $classroomId;

    public function __construct($classroomId = null)
    {
        $this->classroomId = $classroomId;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = Student::with(['user', 'classroom'])
            ->orderBy('entry_year', 'DESC');

        if ($this->classroomId) {
            $query->where('classroom_id', $this->classroomId);
        }

        return $query->get();
    }

    public function map($student): array
    {
        return [
            $student->nisn ?? '-',
            $student->nis ?? '-',
            $student->user->name ?? '-',
            $student->gender?->label() ?? '-',
            $student->classroom->name ?? '-',
            $student->date_of_birth?->format('d/m/Y') ?? '-',
            $student->entry_year ?? '-',
            $student->parent_name ?? '-',
            $student->parent_phone ?? '-',
            $student->address ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NISN',
            'NIS',
            'Nama Siswa',
            'Jenis Kelamin',
            'Kelas',
            'Tanggal Lahir',
            'Tahun Masuk',
            'Nama Orang Tua',
            'No. Telepon Orang Tua',
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
            'A' => 15,
            'B' => 15,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 12,
            'H' => 25,
            'I' => 18,
            'J' => 40,
        ];
    }

    public function title(): string
    {
        return 'Data Siswa';
    }
}
