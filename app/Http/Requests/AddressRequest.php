<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user_id),
            ],
            'name'              => 'required|string|max:255',
            'profile_picture'   => 'nullable|file|image|mimes:png,jpg,jpeg|max:2048',
            'nomor_handphone'   => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'kode_provinsi'     => 'nullable|exists:masterdata_provinsi,kode_provinsi',
            'kode_kota'         => 'nullable|exists:masterdata_kota,kode_kota',
            'kode_kecamatan'    => 'nullable|exists:masterdata_kecamatan,kode_kecamatan',
            'kode_kelurahan'    => 'nullable|exists:masterdata_kelurahan,kode_kelurahan',
            'kode_pos'          => 'nullable|string|max:6|regex:/^[0-9]+$/',
            'alamat_lengkap'    => 'nullable|string|max:100',
            'catatan'           => 'nullable|string|max:100',
            'remove_photo'      => 'nullable',
        ];
    }

    /**
     * Custom error message (optional)
     */
    public function messages(): array
    {
        return [
            // Username
            'username.required'         => 'Username wajib diisi.',
            'username.string'           => 'Username harus berupa teks.',
            'username.max'              => 'Username tidak boleh lebih dari 20 karakter.',
            'username.unique'           => 'Username sudah digunakan, silakan pilih username lain.',

            // Name
            'name.required'             => 'Nama lengkap wajib diisi.',
            'name.string'               => 'Nama lengkap harus berupa teks.',
            'name.max'                  => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            // Profile Picture
            'profile_picture.file'      => 'Foto profil harus berupa file.',
            'profile_picture.image'     => 'Foto profil harus berupa gambar.',
            'profile_picture.mimes'     => 'Foto profil hanya dapat berupa file dengan format: png, jpg, atau jpeg.',
            'profile_picture.max'       => 'Foto profil tidak boleh lebih dari 2 MB.',
            'remove_photo.boolean'      => 'Nilai remove photo harus berupa true atau false.',

            // Nomor Handphone
            'nomor_handphone.regex'     => 'Nomor handphone harus berupa angka dengan panjang antara 10 hingga 15 karakter.',

            // Kode Provinsi
            'kode_provinsi.required'    => 'Kode provinsi wajib diisi.',
            'kode_provinsi.exists'      => 'Kode provinsi yang dipilih tidak valid.',

            // Kode Kota
            'kode_kota.required'        => 'Kode kota wajib diisi.',
            'kode_kota.exists'          => 'Kode kota yang dipilih tidak valid.',

            // Kode Kecamatan
            'kode_kecamatan.required'   => 'Kode kecamatan wajib diisi.',
            'kode_kecamatan.exists'     => 'Kode kecamatan yang dipilih tidak valid.',

            // Kode Kelurahan
            'kode_kelurahan.exists'     => 'Kode kelurahan yang dipilih tidak valid.',
            'kode_pos.required'         => 'Kode pos wajib diisi.',

            // Kode Pos
            'kode_pos.regex'            => 'Kode pos harus berupa angka.',

            // Alamat Lengkap
            'alamat_lengkap.required'   => 'Alamat lengkap wajib diisi.',
            'alamat_lengkap.max'        => 'Alamat lengkap tidak boleh lebih dari 100 karakter.',

            // Catatan
            'catatan.max'               => 'Catatan tidak boleh lebih dari 100 karakter.',
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
