<?php

namespace App\Http\Requests\HomeroomTeacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomeroomTeacherRequest extends FormRequest
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
        // Adjust the route key name if your route uses a different parameter name.
        $homeroomTeacher = $this->route('homeroom_teacher');
        $homeroomId = optional($homeroomTeacher)->id ?? $homeroomTeacher;

        return [
            'teacher_id' => [
                'required',
                'exists:teachers,id',
                Rule::unique('homeroom_teachers', 'teacher_id')->ignore($homeroomId),
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                Rule::unique('homeroom_teachers', 'classroom_id')->ignore($homeroomId),
            ],
        ];
    }

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
