<?php

namespace App\Http\Requests\ManagementSystem;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
        $role_id = $this->route('id');
        $role_id = $role_id ? Crypt::decrypt($role_id) : null;

        return [
            'role_name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role_id)
            ],
            'display_name'  => 'required|string|max:255',
            'description'   => 'required|string',
            'is_active'     => 'required|boolean',
            'type_role'     => 'required|string|max:255',
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'role_name.required'        => 'Nama Role wajib diisi.',
            'role_name.string'          => 'Nama Role harus berupa teks.',
            'role_name.max'             => 'Nama Role tidak boleh lebih dari 255 karakter.',
            'role_name.unique'          => 'Nama Role sudah digunakan.',

            'display_name.required'     => 'Display Name wajib diisi.',
            'display_name.string'       => 'Display Name harus berupa teks.',
            'display_name.max'          => 'Display Name tidak boleh lebih dari 255 karakter.',

            'description.required'      => 'Deskripsi wajib diisi.',
            'description.string'        => 'Deskripsi harus berupa teks.',

            'is_active.required'        => 'Status Role harus diisi.',
            'is_active.boolean'         => 'Status Role harus bernilai aktif atau tidak aktif.',
            
            'type_role.required'        => 'Tipe Role wajib diisi.',
            'type_role.string'          => 'Tipe Role harus berupa teks.',
            'type_role.max'             => 'Tipe Role tidak boleh lebih dari 255 karakter.',
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
