@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Menu dan Hak Akses') --}}
@section('title', 'Menu dan Hak Akses')
@section('menu-hak-akses', 'here')

@section('content')
    @include('layouts.dashboard_components.toolbar')

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card">
                @if ($accessModifyData || $menu_access['can_update'])
                    <div class="card-header justify-content-end">
                        <div class="card-title">
                            @if (!empty($roles) && $currentRoleId)
                                <select id="role_id" class="form-select" aria-label="Pilih Role" data-control="select2" data-hide-search="true">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->encrypted_id }}" {{ Illuminate\Support\Facades\Crypt::decrypt($role->encrypted_id) == $currentRoleId ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-muted">Role data tidak tersedia.</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Body --}}
                <div class="card-body py-4">
                    @if (!empty($menus) && collect($menus)->count() > 0)
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table">
                            {{-- Body --}}

                            <thead class="text-gray-600 fw-semibold">
                                <tr>
                                    <td>Menu</td>
                                    <td>View</td>
                                    <td>Create</td>
                                    <td>Update</td>
                                    <td>Delete</td>
                                    <td class="text-center">More Action</td>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($menus as $menu)
                                    <tr>
                                        <!-- Kolom Menu -->
                                        <td class="text-gray-800"
                                            data-bs-toggle="tooltip"
                                            data-bs-html="true"
                                            title="Menu Key: {{ $menu['key'] }}<br>Path: {{ $menu['path'] }}">
                                            {{ $menu['menu_name'] }}
                                        </td>

                                        <!-- Kolom Permission Actions Prioritas -->
                                        @foreach (['view', 'create', 'update', 'delete'] as $action)
                                            @php
                                                $permission = collect($menu['permissions'])->firstWhere('permission_action', $action);
                                            @endphp
                                            <td>
                                                <div class="d-flex">
                                                    @if ($permission)
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="{{ $permission['encrypt_id'] }}"
                                                                id="permission_{{ $permission['encrypt_id'] }}"
                                                                name="permissions[{{ $menu['id'] }}][]"
                                                                {{ $permission['is_allowed'] ? 'checked' : '' }}
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $permission['permission_name'] }}
                                                                {{ !$accessModifyData || !$menu_access['can_update'] ? 'disabled' : '' }}"
                                                            />
                                                            {{-- <span class="form-check-label"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $permission['permission_name'] }}">
                                                                {{ ucfirst($action) }}
                                                            </span> --}}
                                                        </label>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endforeach

                                        <!-- Kolom untuk Dropdown More Actions -->
                                        <td class="text-center">
                                            @php
                                                // Filter hanya untuk permission tambahan
                                                $additional_permissions = collect($menu['permissions'])
                                                    ->whereNotIn('permission_action', ['view', 'create', 'update', 'delete'])
                                                    ->values(); // Reset index agar rapi
                                            @endphp

                                            @if ($additional_permissions->isNotEmpty())
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                            id="dropdownMenuButton{{ $menu['id'] }}"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        More Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $menu['id'] }}">
                                                        @foreach ($additional_permissions as $permission)
                                                            <li class="p-2">
                                                                {{-- Hentikan aksi parent event --}}
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid" onclick="event.stopPropagation()">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="{{ $permission['encrypt_id'] }}"
                                                                        id="permission_{{ $permission['encrypt_id'] }}"
                                                                        name="permissions[{{ $menu['id'] }}][]"
                                                                        {{ $permission['is_allowed'] ? 'checked' : '' }}
                                                                        {{ !$accessModifyData || !$menu_access['can_update'] ? 'disabled' : '' }}
                                                                    />
                                                                    <span class="form-check-label">
                                                                        {{ ucfirst($permission['permission_action']) }}
                                                                    </span>
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center fw-2 py-5 my-5">Ups.... data menu saat ini tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let accessModifyData = {{ $accessModifyData ? 'true' : 'false' }};
            let canUpdateMenu = {{ $menu_access['can_update'] ? 'true' : 'false' }};

            if (!accessModifyData || !canUpdateMenu) {
                $('input[type="checkbox"]').attr('disabled', true);
                $('#role_id').attr('disabled', true);
            }

            // Ketika role dipilih
            $('#role_id').on('change', function () {
                if (!accessModifyData || !canUpdateMenu) return;
                let roleId = $(this).val();

                // Jika tidak ada role yang dipilih
                if (!roleId) {
                    $('#table tbody').html('<tr><td colspan="6" class="text-center text-muted">Silahkan pilih role untuk melihat hak akses.</td></tr>');
                    return;
                }

                // fetch menus by role
                $.ajax({
                    url: "{{ route('menu-hak-akses.fetch-menus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        role_id: roleId,
                    },
                    success: function (response) {
                        if (response.metadata.status === 'success') {
                            updateMenuTable(response.response);
                        } else {
                            toastr.error(response.metadata.message, "Error");
                        }
                    },
                    error: function () {
                        toastr.error("Gagal mengambil data menu.", "Error");
                        $('#table tbody').html('<tr><td colspan="6" class="text-center text-muted">Terjadi kesalahan, gagal dalam memuat data menu.</td></tr>');
                    }
                });
            });

            // Update Menu Tabel
            function updateMenuTable(menus) {
                let tableBody = $('#table tbody');
                tableBody.empty();

                if (!menus || menus.length === 0) {
                    tableBody.append('<tr><td colspan="6" class="text-center text-muted">Tidak ada hak akses izin menu admin untuk role ini.</td></tr>');
                    return;
                }

                menus.forEach(menu => {
                    let row = `
                        <tr>
                            <td class="text-gray-800" data-bs-toggle="tooltip" title="Menu Key: ${menu.key}<br>Path: ${menu.path}">
                                ${menu.menu_name}
                            </td>
                    `;

                    // Iterasi untuk permission actions seperti view, create, update, delete
                    ['view', 'create', 'update', 'delete'].forEach(action => {
                        let permission = menu.permissions.find(p => p.permission_action === action);
                        row += `
                            <td>
                                <div class="d-flex">
                                    ${permission
                                        ? `<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox"
                                                value="${permission.encrypt_id}"
                                                ${permission.is_allowed ? 'checked' : ''}
                                                data-bs-toggle="tooltip"
                                                ${accessModifyData ? '' : 'disabled'}
                                                ${accessModifyData && canUpdateMenu ? '' : 'disabled'}" />
                                        </label>`
                                        : '<span class="text-muted">-</span>'
                                    }
                                </div>
                            </td>
                        `;
                    });

                    // Menambahkan bagian More Action jika ada permissions tambahan
                    let additional_permissions = menu.permissions.filter(p => !['view', 'create', 'update', 'delete'].includes(p.permission_action));
                    if (additional_permissions.length > 0) {
                        row += `
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton${menu.id}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            ${accessModifyData ? '' : 'disabled'}>
                                        More Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${menu.id}">
                                        ${additional_permissions.map(permission => `
                                            <li class="p-2">
                                                <label class="form-check form-check-sm form-check-custom form-check-solid" onclick="event.stopPropagation()">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="${permission.encrypt_id}"
                                                        id="permission_${permission.encrypt_id}"
                                                        name="permissions[${menu.id}][]"
                                                        ${permission.is_allowed ? 'checked' : ''}
                                                        ${accessModifyData && canUpdateMenu ? '' : 'disabled'} />
                                                    <span class="form-check-label">
                                                        ${permission.permission_action.charAt(0).toUpperCase() + permission.permission_action.slice(1)}
                                                    </span>
                                                </label>
                                            </li>
                                        `).join('')}
                                    </ul>
                                </div>
                            </td>
                        `;
                    } else {
                        row += '<td class="text-center"><span class="text-muted">-</span></td>';
                    }

                    row += '</tr>';
                    tableBody.append(row);
                });
            }

            // Update
            // Event delegation untuk menangani perubahan pada checkbox
            $('#table').on('change', 'input[type="checkbox"]', function () {
                if (!accessModifyData || !canUpdateMenu) return;

                let permissionId = $(this).val();
                let isAllowed = $(this).is(':checked') ? 1 : 0;
                let roleId = $('#role_id').val();

                $.ajax({
                    url: "{{ route('menu-hak-akses.update') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        permission_id: permissionId,
                        is_allowed: isAllowed,
                        role_id: roleId,
                    },
                    success: function (response) {
                        if (response.metadata.status === 'success') {
                            toastr.success(response.metadata.message, "Berhasil");
                        } else {
                            toastr.error(response.metadata.message, "Error");
                        }
                    },
                    error: function () {
                        toastr.error("Gagal memperbarui data permission.", "Error");
                    }
                });
            });
        });
    </script>
@endpush
