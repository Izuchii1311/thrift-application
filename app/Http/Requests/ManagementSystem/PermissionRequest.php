<?php

namespace App\Http\Requests\ManagementSystem;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionRequest extends FormRequest
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
        $permission_id = $this->route('id');
        $permission_id = $permission_id ? Crypt::decrypt($permission_id) : null;

        return [
            'menu_id'           => 'required|integer|exists:menus,id',
            'permission_name'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->ignore($permission_id)
            ],
            'permission_action' => 'required|string|max:255',
            'description'       => 'required|string'
        ];
    }

    /**
     * Custom error message (optional)
     */
    public function messages(): array
    {
        return [
            'menu_id.required'              => 'Menu wajib dipilih.',
            'menu_id.integer'               => 'Menu harus berupa angka.',
            'menu_id.exists'                => 'Menu yang dipilih tidak valid.',

            'permission_name.required'      => 'Nama Permission wajib diisi.',
            'permission_name.string'        => 'Nama Permission harus berupa teks.',
            'permission_name.max'           => 'Nama Permission tidak boleh lebih dari 255 karakter.',
            'permission_name.unique'        => 'Nama Permission sudah digunakan.',
            
            'permission_action.required'    => 'Action Permission wajib diisi.',
            'permission_action.string'      => 'Action Permission harus berupa teks.',
            'permission_action.max'         => 'Action Permission tidak boleh lebih dari 255 karakter.',

            'description.required'          => 'Deskripsi wajib diisi.',
            'description.string'            => 'Deskripsi harus berupa teks.'
        ];
    }

    /**
     * Handle a failed validation attempt
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
                'response'  => $datas
            ])
        );
    }
}
