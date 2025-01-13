<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ManagementSystem\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;

class ManagementUserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('management-user');
        return view('dashboard.management_system.users.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = User::select('*');

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('users.username',     'ilike', '%' . $request->search . '%')
                        ->orWhere('users.name',         'ilike', '%' . $request->search . '%')
                        ->orWhere('users.email',        'ilike', '%' . $request->search . '%');
                });
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_info', function ($row) {
                if ($row === null) {
                    return '-';
                }

                $profilePicture = $row->profile_picture
                    ? '<img src="' . asset('storage/' . $row->profile_picture) . '" alt="' . Str::title($row->name) . '" class="w-100 h-100" style="object-fit: cover;" />'
                    : '<div class="symbol-label fs-3 bg-light-danger text-danger">' . Str::upper(substr($row->name, 0, 1)) . '</div>';

                return '
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <a>
                                <div class="symbol-label">
                                    ' . $profilePicture . '
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a class="text-gray-800 text-hover-primary mb-1">' . Str::title($row->name) . '</a>
                            <span>' . $row->email . '</span>
                        </div>
                    </div>';
            })
            ->addColumn('roles', function ($row) {
                return $row->roles->map(function ($role) {
                    $badgeColor = '';
                    switch ($role->type_role) {
                        case 'superadmin':
                            $badgeColor = 'badge-light-primary';
                            break;
                        case 'admin':
                            $badgeColor = 'badge-light-success';
                            break;
                        case 'staff':
                            $badgeColor = 'badge-light-warning';
                            break;
                        default:
                            $badgeColor = 'badge-light-danger';
                    }

                    return [
                        'role_name'     => $role->role_name,
                        'type_role'     => $role->type_role,
                        'badge_color'   => $badgeColor,
                    ];
                })->map(function ($roleData) {
                    return '<span class="badge ' . $roleData['badge_color'] . '">' . $roleData['role_name'] . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('management-user');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete);
            })
            ->addColumn('is_active', function($row) {
                if ($row->is_active) {
                    return '<div class="badge badge-light-success">Aktif</div>';
                } else {
                    return '<div class="badge badge-light-danger">Tidak Aktif</div>';
                }
            })
            ->rawColumns(['user_info', 'roles', 'action', 'is_active'])
            ->toArray();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $validated              = $request->validated();
            $validated['password']  = Hash::make($validated['password']);

            if ($request->hasFile('profile_picture')) {
                $filePath = $this->storeFile($request->file('profile_picture'), 'users/profile');

                if (!$filePath) {
                    throw new \Exception('Gagal menyimpan file.');
                }

                $validated['profile_picture'] = $filePath;
            }

            DB::beginTransaction();
            $user = User::create($validated);
            $user->save();

            $roles = $validated['user_roles'];

            $rolesWithStatus = collect($roles)->mapWithKeys(function ($roleId, $index) {
                return [
                    $roleId => ['is_active' => $index === 0],
                ];
            })->toArray();

            $user->roles()->sync($rolesWithStatus);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data User baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                $this->deleteEmptyDirectory(dirname($filePath));
            }

            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($encryptedId)
    {
        try {
            $user_id    = Crypt::decrypt($encryptedId);
            $user       = User::with(['roles:id,role_name,is_active'])
            ->select('id', 'username', 'name', 'email', 'profile_picture')
            ->find($user_id);

            return $user
                ? $this->api_response_success('Berhasil menampilkan data User.', $user->toArray())
                : $this->api_response_error('Data User tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(UserRequest $request, $encryptedId)
    {
        try {
            $user_id    = Crypt::decrypt($encryptedId);
            $user       = User::where('id', $user_id)->first();

            if (!$user) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            $validated = $request->validated();

            $oldFilePath = $user->profile_picture;
            $newFilePath = null;

            DB::beginTransaction();
            if ($request->hasFile('profile_picture')) {
                $newFilePath = $this->storeFile($request->file('profile_picture'), 'users/profile');

                if (!$newFilePath) {
                    throw new \Exception('Gagal menyimpan file.');
                }

                $validated['profile_picture'] = $newFilePath;
            } else {
                // $validated['profile_pictute'] = $oldFilePath;
                if ($request->input('remove_photo') == 'true') {
                    // Hapus file lama jika ada
                    if (Storage::disk('public')->exists($oldFilePath)) {
                        Storage::disk('public')->delete($oldFilePath);
                        $this->deleteEmptyDirectory(dirname($oldFilePath));
                    }
                    // Set file path ke null jika ingin menghapus gambar
                    $validated['profile_picture'] = null;
                } else {
                    $validated['profile_picture'] = $oldFilePath;
                }
            }

            $user->update($validated);

            $roles = $validated['user_roles'];

            $rolesWithStatus = collect($roles)->mapWithKeys(function ($roleId, $index) {
                return [
                    $roleId => ['is_active' => $index === 0],
                ];
            })->toArray();

            $user->roles()->sync($rolesWithStatus);
            DB::commit();

            if ($newFilePath && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
                $this->deleteEmptyDirectory(dirname($newFilePath));
            }

            return $this->api_response_success('Berhasil memperbarui data User.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            if (isset($newFilePath) && Storage::disk('public')->exists($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
                $this->deleteEmptyDirectory(dirname($newFilePath));
            }
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $user_id    = Crypt::decrypt($encryptedId);
            $user       = User::where('id', $user_id)->first();

            if (!$user) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            $user_profile_picture = $user->profile_picture;

            DB::beginTransaction();
            $user->roles()->detach();
            $user->delete();
            DB::commit();

            if (!empty($user_profile_picture) && Storage::disk('public')->exists($user_profile_picture)) {
                Storage::disk('public')->delete($user_profile_picture);
                $this->deleteEmptyDirectory(dirname($user_profile_picture));
            }

            return $this->api_response_success('Berhasil menghapus data User.', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
