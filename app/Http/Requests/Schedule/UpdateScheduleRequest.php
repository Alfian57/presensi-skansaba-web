<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $schoolDayValues = array_column(\App\Enums\Day::schoolDays(), 'value');

        return [
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'day' => ['required', 'in:' . implode(',', $schoolDayValues)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['required', 'string', 'max:100'],
            'academic_year' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages()
    {
        $schoolDayValues = array_column(\App\Enums\Day::schoolDays(), 'value');

        return [
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.exists' => 'Kelas tidak ditemukan.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
            'teacher_id.required' => 'Guru wajib dipilih.',
            'teacher_id.exists' => 'Guru tidak ditemukan.',
            'day.required' => 'Hari wajib dipilih.',
            'day.in' => 'Hari harus salah satu dari: ' . implode(', ', $schoolDayValues) . '.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'room.required' => 'Ruangan wajib diisi.',
            'room.string' => 'Ruangan harus berupa teks.',
            'room.max' => 'Ruangan maksimal :max karakter.',
            'academic_year.required' => 'Tahun akademik wajib diisi.',
            'academic_year.regex' => 'Tahun akademik harus dalam format YYYY/YYYY.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.in' => 'Semester harus 1 atau 2.',
        ];
    }
}
