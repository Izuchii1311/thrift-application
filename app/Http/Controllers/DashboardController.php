<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Menu;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

class DashboardController extends BaseController
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function changeRole(Request $request)
    {
        try {
            $rules = [
                'role_id' => 'required|exists:roles,id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->api_response_validator('Terjadi kesalahan pada sistem mengenai role yang dipilih.', []);
            }

            $validated = $validator->validate();

            DB::beginTransaction();

            DB::table('role_users')
                ->where('user_id', Auth::id())
                ->where('role_id', Auth::user()->roles->firstWhere('pivot.is_active', true)->pivot->role_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            DB::table('role_users')
                ->where('user_id', Auth::id())
                ->where('role_id', $validated['role_id'])
                ->update(['is_active' => true]);

            DB::commit();

            return $this->api_response_success('Berhasil mengganti Role', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
