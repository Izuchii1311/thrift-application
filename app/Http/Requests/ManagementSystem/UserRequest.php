<?php

namespace App\Http\Requests\ManagementSystem;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user_id = $this->route('id');
        $user_id = $user_id ? Crypt::decrypt($user_id) : null;

        return [
            'username'          => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user_id),
            ],
            'name'              => 'required|string|max:255',
            'email'             => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user_id),
            ],
            'password'          => $user_id ? 'nullable|string|min:8' : 'required|string|min:8',
            'profile_picture'   => 'nullable|file|image|mimes:png,jpg,jpeg|max:2048',
            'is_active'         => 'required|boolean',
            'user_roles'        => 'required|array|min:1',
            'user_roles.*'      => 'integer|exists:roles,id',
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'username.required'     => 'Username wajib diisi.',
            'username.string'       => "Username harus berupa teks.",
            'username.max'          => "Username tidak boleh lebih dari 20 karakter.",
            'username.unique'       => 'Username sudah digunakan.',

            'name.required'         => 'Nama wajib diisi.',
            'name.string'           => "Name harus berupa teks.",
            'name.max'              => "Name tidak boleh lebih dari 255 karakter.",

            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan.',
            'email.max'             => "Email tidak boleh lebih dari 255 karakter.",

            'password.required'     => 'Password wajib diisi.',
            'password.string'       => "Password harus berupa teks.",
            'password.min'          => 'Password minimal 8 karakter.',

            'profile_picture.mimes' => 'Format file harus png, jpg, atau jpeg.',
            'profile_picture.max'   => 'Ukuran file maksimal 2MB.',

            'is_active.required'    => 'Status akun harus diisi.',
            'is_active.boolean'     => 'Status akun harus bernilai aktif atau tidak aktif.',
            
            'user_roles.required'   => 'Role pengguna harus dipilih.',
            'user_roles.array'      => 'Role pengguna harus berupa array.',
            'user_roles.min'        => 'Setidaknya pilih satu role pengguna.',
            'user_roles.*.integer'  => 'Setiap role harus berupa angka.',
            'user_roles.*.exists'   => 'Role yang dipilih tidak valid.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->api_response_validator(
                'Periksa kembali data yang anda isi!',
                [],
                $validator->errors()->toArray(),
                422
            )
        );
    }

    /**
     * Custom API response for validation errors.
     *
     * @param String $message
     * @param Array $datas
     * @param Array $errors
     * @param Int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    private function api_response_validator(String $message, Array $datas = [], Array $errors = [], Int $status_code = 400)
    {
        return response()->json(
            array_merge([
                'metadata'  => [
                    'status'        => 'error',
                    'message'       => $message,
                    'status_code'   => $status_code,
                    'errors'        => $errors,
                ],
                'response'  => $datas,
            ])
        );
    }
}
