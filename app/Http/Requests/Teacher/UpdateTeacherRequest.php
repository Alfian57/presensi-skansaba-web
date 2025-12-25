<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher')?->id;
        $userId = $this->route('teacher')?->user_id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$userId],
            'username' => ['nullable', 'string', 'unique:users,username,'.$userId],
            'password' => ['nullable', 'string', 'min:6'],
            'nip' => ['sometimes', 'required', 'string', 'unique:teachers,nip,'.$teacherId],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['required', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'delete_profile_picture' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama guru wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'username.unique' => 'Username sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
            'nip.required' => 'NIP/NIK wajib diisi.',
            'nip.unique' => 'NIP/NIK sudah terdaftar.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
        ];
    }
}
