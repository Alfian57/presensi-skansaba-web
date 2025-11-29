<?php

namespace App\Http\Requests\HomeroomTeacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeroomTeacherRequest extends FormRequest
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
            'teacher_id' => 'required|exists:teachers,id|unique:homeroom_teachers,teacher_id',
            'classroom_id' => 'required|exists:classrooms,id|unique:homeroom_teachers,classroom_id',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'teacher_id.unique' => 'Guru sudah menjadi wali kelas.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.unique' => 'Kelas sudah memiliki wali kelas.',
        ];
    }
}
