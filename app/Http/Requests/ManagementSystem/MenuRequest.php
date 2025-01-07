<?php

namespace App\Http\Requests\ManagementSystem;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MenuRequest extends FormRequest
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
        $menu_id = $this->route('id');
        $menu_id = $menu_id ? Crypt::decrypt($menu_id) : null;

        return [
            'menu_name'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus')->ignore($menu_id)
            ],
            'path'        => 'nullable|string|max:255',
            'key'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus')->ignore($menu_id)
            ],
            'menu_icon'   => 'nullable|string|max:255',
            'parent_id'   => 'nullable|integer|exists:menus,id',
            'ordering'    => 'required|integer'
        ];
    }

    /**
     * Custom error message (optional)
     */
    public function messages(): array
    {
        return [
            'menu_name.required'        => "Nama Menu wajib diisi.",
            'menu_name.string'          => "Nama Menu harus berupa teks.",
            'menu_name.max'             => "Nama Menu tidak boleh lebih dari 255 karakter.",
            'menu_name.unique'          => "Nama Menu sudah digunakan.",

            'path.string'               => "Path harus berupa teks.",
            'path.max'                  => "Path tidak boleh lebih dari 255 karakter.",

            'key.required'              => "Key Menu wajib diisi.",
            'key.string'                => "Key harus berupa teks.",
            'key.max'                   => "Key tidak boleh lebih dari 255 karakter.",
            'key.unique'                => "Key Menu sudah digunakan",

            'menu_icon.string'          => "Menu Icon harus berupa teks.",
            'menu_icon.max'             => "Menu Icon tidak boleh lebih dari 255 karakter.",

            'parent_id.integer'         => "Parent Menu harus berupa angka.",
            'parent_id.exists'          => "Parent Menu yang dipilih tidak valid.",

            'ordering.required'         => "Ordering wajib diisi.",
            'ordering.integer'          => "Ordering harus berupa angka."
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
                'response'  => $datas,
            ])
        );
    }
}
