<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Return API Response Success
     *
     * @param String $message
     * @param Array $datas
     * @param Array $additional_datas
     * @param Int $status_code
     * @return JsonResponse
     */
    public function api_response_success(String $message, Array $datas = [], Array $additional_datas = [], Int $status_code = 200): JsonResponse
    {
        return response()->json(
            array_merge([
                'metadata' => [
                    'status'        => 'success',
                    'message'       => $message,
                    'status_code'   => $status_code
                ],
                'response'  => $datas,
            ], $additional_datas)
        , $status_code);
    }

    /**
     * Return API Response Error
     *
     * @param String $message
     * @param Array $datas
     * @param Array $errors
     * @param Int $status_code
     * @return JsonResponse
     */
    public function api_response_error(String $message, Array $datas = [], Array $errors = [], Int $status_code = 500): JsonResponse
    {
        return response()->json(
            array_merge([
                'metadata'  => [
                    'status'        => 'error',
                    'message'       => $message,
                    'status_code'   => $status_code,
                    'errors'        => $errors
                ],
                'response'  => $datas
            ])
        , $status_code);
    }

    /**
     * Return API Response Validator Error
     *
     * @param String $mssage
     * @param Array $datas
     * @param Array $errors
     * @param Int $status_code
     * @return JsonResponse
     */
    public function api_response_validator(String $message, Array $datas = [], Array $errors = [], Int $status_code = 400): JsonResponse
    {
        return response()->json(
            array_merge([
                'metadata'  => [
                    'status'        => 'error',
                    'message'       => $message,
                    'status_code'   => $status_code,
                    'errors'        => $errors
                ],
                'response'  => $datas
            ])
        );
    }

    /**
     * Store Or Genarate File With Specific Folder Structure
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param String $folder
     * @param String $fileName (Optional)
     * @return String $fileName
     */
    protected function storeFile($file, $folder, $fileName = null)
    {
        if (!$file || !$folder) {
            $this->api_response_error('Parameter file harus diisi');
        }

        $user_id    = Auth::id() ?? 'guest';
        $date       = Carbon::now();
        $filePath   = implode('/', [$folder, $date->year, $date->month, $date->day, $user_id]);

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $storeFile = $file->storeAs($filePath, $file->hashName(), 'public');
        } else {
            $storeFile = Storage::disk('public')->put(implode('/', [$filePath, $fileName]), $file);
        }

        return $storeFile;
    }

    /**
     * Delete Empty Directory File Storage
     *
     * @param String $directory
     * @return Void
     */
    protected function deleteEmptyDirectory(String $directory): void
    {
        if (!$directory) {
            $this->api_response_error('Parameter file harus diisi');
        }

        $fullPath = storage_path('app/public/' . $directory);

        if (!$fullPath) return;

        $files = array_diff(scandir($fullPath), ['.', '..']);

        if (empty($files)) {
            // remove directory
            rmdir($fullPath);

            // remove parent directory
            $parentDirectory = dirname($directory);
            if ($parentDirectory !== '.' && $parentDirectory !== '/') {
                $this->deleteEmptyDirectory($parentDirectory);
            }
        }
    }
}
