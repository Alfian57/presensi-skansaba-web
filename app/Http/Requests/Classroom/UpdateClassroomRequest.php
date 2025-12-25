<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
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
        return [
            'grade_level' => ['required', 'integer', 'min:10', 'max:12'],
            'major' => ['required', 'string'],
            'class_number' => ['required', 'integer', 'min:1'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'grade_level.required' => 'Tingkat kelas wajib dipilih.',
            'grade_level.integer' => 'Tingkat kelas harus berupa angka.',
            'grade_level.min' => 'Tingkat kelas minimal :min.',
            'grade_level.max' => 'Tingkat kelas maksimal :max.',
            'major.required' => 'Jurusan wajib dipilih.',
            'major.string' => 'Jurusan harus berupa teks.',
            'class_number.required' => 'Nomor kelas wajib diisi.',
            'class_number.integer' => 'Nomor kelas harus berupa angka.',
            'class_number.min' => 'Nomor kelas minimal :min.',
            'is_active.boolean' => 'Status aktif harus bernilai true atau false.',
        ];
    }
}
