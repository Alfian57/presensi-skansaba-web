<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Matematika',
            'Fisika',
            'Kimia',
            'PPKN',
            'Pemrograman Berorientasi Objek',
            'Pemrograman Web dan Perangkat Bergerak',
            'Basis Data',
            'Bahasa Jawa',
        ];

        foreach ($data as $item) {
            Subject::create([
                'name' => $item,
                'slug' => Str::slug($item),
                'code' => 'MK-' . date('Y') . '-' . Str::slug($item),
            ]);
        }
    }
}
