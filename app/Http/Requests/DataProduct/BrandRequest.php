<?php

namespace App\Http\Requests\DataProduct;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
        $brand_slug = $this->route('slug');

        return [
            'brand_name' => [
                'required',
                'string',
                'max:25',
                Rule::unique('brands')->ignore($brand_slug, 'slug')
            ],
        ];
    }

    /**
     * Custom error message (optional)
     */
    public function messages(): array
    {
        return [
            'brand_name.required'    => 'Nama Brand wajib diisi.',
            'brand_name.string'      => 'Nama Brand harus berupa teks.',
            'brand_name.max'         => 'Nama Brand tidak boleh lebih dari 255 karakter.',
            'brand_name.unique'      => 'Nama Brand sudah digunakan.',
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
