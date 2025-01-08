<?php

namespace App\Http\Requests\Keuangan;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ManagementKeuanganRequest extends FormRequest
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
        $keuangan_id = $this->route('id');
        $keuangan_id = $keuangan_id ? Crypt::decrypt($keuangan_id) : null;

        return [
            'modal_pemasukan'   => 'required|integer|min:0',
            'modal_pengeluaran' => 'nullable|integer|min:0',
            'periode'           => 'required',
            'catatan'           => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error message (optional)
     */
    public function messages(): array
    {
        return [
            'modal_pemasukan.required'    => 'Modal pemasukan wajib diisi.',
            'modal_pemasukan.integer'     => 'Modal pemasukan harus berupa angka.',
            'modal_pemasukan.min'         => 'Modal pemasukan tidak boleh kurang dari 0.',

            'modal_pengeluaran.integer'   => 'Modal pengeluaran harus berupa angka.',
            'modal_pengeluaran.min'       => 'Modal pengeluaran tidak boleh kurang dari 0.',

            'periode.required'            => 'Periode wajib diisi.',
            'periode.date'                => 'Periode harus berupa tanggal yang valid.',

            'catatan.string'              => 'Catatan harus berupa teks.',
            'catatan.max'                 => 'Catatan tidak boleh lebih dari 1000 karakter.',
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

