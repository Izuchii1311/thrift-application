@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Management User') --}}
@section('title', 'Management User')
@section('management-user', 'here')

@section('content')
    @include('layouts.dashboard_components.toolbar')

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                {{-- Header --}}
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        {{-- Search --}}
                        <div class="d-flex align-items-center position-relative my-1">
                            <input type="text" class="form-control form-control-solid w-250px" placeholder="Search" id="search" />
                            <button onclick="search()" type="button" class="ms-1 btn btn-light-primary">
                                <i class="ki-outline ki-magnifier"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Card Toolbar --}}
                    <div class="card-toolbar">
                        {{-- Toolbar --}}
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            {{-- Filter --}}
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-filter fs-2"></i>Filter</button>

                            {{-- Filter Options --}}
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                </div>
                                <div class="separator border-gray-200"></div>

                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                    {{-- Filter type --}}
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-semibold">Role:</label>
                                        <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Analyst">Analyst</option>
                                            <option value="Developer">Developer</option>
                                            <option value="Support">Support</option>
                                            <option value="Trial">Trial</option>
                                        </select>
                                    </div>

                                    {{-- Filter type --}}
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-semibold">Two Step Verification:</label>
                                        <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true">
                                            <option></option>
                                            <option value="Enabled">Enabled</option>
                                        </select>
                                    </div>

                                    {{-- Action --}}
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Export --}}
                            @if ($menu_access['can_export'])
                                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
                            @endif

                            {{-- Add New Data --}}
                            @if ($menu_access['can_create'])
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form_modal" onclick="tambahData()">
                                <i class="ki-outline ki-plus fs-2"></i>Tambah User Baru</button>
                            @endif
                        </div>

                        {{-- Export User --}}
                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                            {{-- Modal --}}
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    {{-- Modal Header --}}
                                    <div class="modal-header">
                                        <h2 class="fw-bold">Export Users</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close" onclick="$('#kt_modal_export_users').modal('hide');">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <form id="kt_modal_export_users_form" class="form" action="#">
                                            {{-- Filter type --}}
                                            <div class="fv-row mb-10">
                                                <label class="fs-6 fw-semibold form-label mb-2">Select Roles:</label>
                                                <select name="role" data-control="select2" data-placeholder="Select a role" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                    <option></option>
                                                    <option value="Administrator">Administrator</option>
                                                    <option value="Analyst">Analyst</option>
                                                    <option value="Developer">Developer</option>
                                                    <option value="Support">Support</option>
                                                    <option value="Trial">Trial</option>
                                                </select>
                                            </div>

                                            {{-- Filter type --}}
                                            <div class="fv-row mb-10">
                                                <label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
                                                <select name="format" data-control="select2" data-placeholder="Select a format" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                    <option></option>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                    <option value="cvs">CVS</option>
                                                    <option value="zip">ZIP</option>
                                                </select>
                                            </div>

                                            {{-- Action --}}
                                            <div class="text-center">
                                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                                    <span class="indicator-label">Submit</span>
                                                    <span class="indicator-progress">Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Add New Data --}}
                        <div class="modal fade" id="form_modal" tabindex="-1" aria-hidden="true">
                            {{-- Modal --}}
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    {{-- Modal Header --}}
                                    <div class="modal-header" id="form_modal_header">
                                        <h2 class="fw-bold" id="form_modal_title">Export Users</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="$('#form_modal').modal('hide');">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="modal-body px-5 my-7">
                                        <form id="form-data" class="form" action="" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="form_modal_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#form_modal_header" data-kt-scroll-wrappers="#form_modal_scroll" data-kt-scroll-offset="300px" >
                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="d-block fw-semibold fs-6 mb-5">Foto profile</label>

                                                    <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true" style="background-image: url('{{ asset("assets/dashboard/media/avatars/blank.png") }}')">
                                                        {{-- Existing Image --}}
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset("assets/dashboard/media/avatars/blank.png") }}');"></div>

                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                            <i class="ki-outline ki-pencil fs-7"></i>
                                                            <input type="file" name="profile_picture" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="remove_photo" id="remove-photo" value="false">
                                                        </label>

                                                        {{-- Button --}}
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                            <i class="ki-outline ki-cross fs-2"></i>
                                                        </span>

                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar" id="remove-btn">
                                                            <i class="ki-outline ki-cross fs-2"></i>
                                                        </span>
                                                    </div>
                                                    <div class="form-text">File yang diizinkan: png, jpg, jpeg.</div>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Username</label>
                                                    <input type="text" name="username" id="username" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Username"/>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Name</label>
                                                    <input type="text" name="name" id="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Email</label>
                                                    <input type="email" name="email" id="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com"/>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7" id="input-password">
                                                    <label class="required fw-semibold fs-6 mb-2">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" placeholder="Password" id="password" name="password" autocomplete="off" class="form-control bg-transparent" />
                                                        <button type="button" onclick="showPassword()" class="btn btn-light-primary btn-icon input-group-text">
                                                            <i id="icon-password" class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Status Akun</label>
                                                    <select class="form-select mb-2" name="is_active" data-control="select2" data-hide-search="true" data-placeholder="Status Akun" id="is_active">
                                                        <option ></option>
                                                        <option value="true" selected="selected">Aktif</option>
                                                        <option value="false">Tidak Aktif</option>
                                                    </select>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="mb-5">
                                                    <label class="required fw-semibold fs-6 mb-5">Role User</label>

                                                    {{-- Input --}}
                                                    <div id="role-options-container">
                                                        {{-- Get data from Ajax --}}
                                                    </div>

                                                    {{-- Separator --}}
                                                    <div class='separator separator-dashed my-5'></div>
                                                </div>

                                            </div>

                                            {{-- Action --}}
                                            <div class="text-center pt-10">
                                                <button type="reset" class="btn btn-light me-3">Reset</button>
                                                <button type="button" class="btn btn-primary" onclick="submitForm()">
                                                    <span class="indicator-label">Simpan</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="card-body py-4">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th class="text-center">Aksi</th>
                                <th class="min-w-125px">User Info</th>
                                <th class="min-w-125px">Username</th>
                                <th class="min-w-125px">Role</th>
                                <th>Status Akun</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let table;
        $(document).ready(function() {
            getRolesOption();

            // Datatable
            table = $('#table').DataTable({
                processing: true, serverSide: true,
                ajax: {
                    url: '{{ route("management-user.indexJson") }}',
                    type: 'POST',
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.search = $('#search').val();
                    },
                    error: function (xhr, error, thrown) {
                        alert('Terjadi kesalahan saat memuat data. Silahkan hubungi administrator mengenai kesalahan sistem apapun.');
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false },
                    { data: 'action',      orderable: false, className: 'text-center' },
                    { data: 'user_info',   orderable: false },
                    { data: 'username',    orderable: true },
                    { data: 'roles',       orderable: false },
                    { data: 'is_active',   orderable: true, className: 'text-center' },
                ],
                language: {
                    emptyTable: "Maaf, data saat ini belum tidak tersedia.",
                    processing: "Memuat data, mohon tunggu..."
                },
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[ 1, "desc" ]]
            });
        });

        $('#search').on('input', function () {
            let searchValue = $(this).val();
            if (searchValue === "") {
                table.draw();
            }
        });

        function search() {
            let searchValue = $('#search').val();
            table.draw();
        }

        function tambahData() {
            $('#form-data').attr('action', '{{ route("management-user.store") }}');

            $('#form-data')[0].reset();
            $('#form-data select').val(null).trigger("change");

            let imageInputWrapper = $('.image-input-wrapper');
            imageInputWrapper.css('background-image', 'url("{{ asset("assets/dashboard/media/avatars/blank.png") }}")');

            $('#remove-photo').val('false');

            $("input[type='file']").val(null);
            getRolesOption([], true);

            $('#form_modal_title').text('Form Tambah User Baru');
            $('#foto_profile, #username, #name, #email, #password').val("");

            $('#form_modal').modal('show');
        }

        function editData(encryptedId) {
            $('#form-data').attr('action', "{{ url('management-user/update') }}" + "/" + encryptedId);

            $('#form_modal_title').text('Edit Data User');
            showLoading();

            $.ajax({
                url: "{{ url('management-user/detail/json') }}" + "/" + encryptedId,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    const user = result.response;

                    $('#username').val(user.username);
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#password, #input-password').attr('disabled', true).hide();

                    if ($('#is_active option[value="' + result.response.is_active+ '"]').length > 0) {
                        $('#is_active').val(result.response.is_active).trigger('change');
                    }

                    let imageInputWrapper = $('.image-input-wrapper');

                    const defaultProfilePicture = "{{ asset('assets/dashboard/media/avatars/blank.png') }}";
                    imageInputWrapper.css('background-image', `url("${defaultProfilePicture}")`);
                    $('#remove-photo').val('false');
                    $("input[type='file']").val(null);

                    if (user.profile_picture !== null && user.profile_picture !== '') {
                        const profilePictureUrl = `{{ asset('storage/${user.profile_picture}') }}`;
                        imageInputWrapper.css('background-image', `url("${profilePictureUrl}")`);

                        $('#remove-btn').show();
                    } else {
                        $('#remove-btn').hide();
                    }

                    const selectedRoles = user.roles.map(role => role.id);
                    getRolesOption(selectedRoles, false);

                    const removeBtn = $('#remove-btn');
                    removeBtn.on('click', function () {
                        $('#profile_picture').val('');
                        $('#remove-photo').val(true);
                        imageInputWrapper.css('background-image', `url('{{ asset('assets/dashboard/media/avatars/blank.png') }}')`);

                        $(this).hide();
                    });


                    $('#form_modal').modal('show');
                    Swal.close();
                },
                error: function(result) {
                    alertResultError(result, "Error, Data User gagal didapatkan.");
                }
            });
        }

        function submitForm() {
            let formData = new FormData($('#form-data')[0]);
            let isActive = formData.get('is_active') === "true" ? '1' : '0';
            formData.set('is_active', isActive);

            Swal.fire({
                title: 'Simpan?',
                text: "Pastikan data yang anda isi telah sesuai!",
                icon: 'warning',
                showDenyButton: true,
                denyButtonText: 'Tidak jangan simpan.',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    $.ajax({
                        url: $('#form-data').attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil menambahkan data.', '#form_modal');
                            } else {
                                handleResError(res);
                            }
                        },
                        error: function (res) {
                            let errorInfo = res.responseJSON.metadata.message || 'Ups.. Sedang terjadi kesalahan pada sistem.';
                            console.log(errorInfo)
                            resErrorSwal('Peringatan!', errorInfo, 'error');
                        }
                    });
                } else if (result.isDenied) {
                    resErrorSwal('Dibatalkan', 'Periksa kembali data yang anda isi.', 'warning')
                }
            });
        }

        function deleteData(encryptedId) {
            Swal.fire({
                title: 'Ingin menghapus Data?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showDenyButton: true,
                denyButtonText: 'Tidak, jangan hapus.',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    $.ajax({
                        url: "{{ url('management-user/delete') }}" + "/" + encryptedId,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil menghapus data.', '#form_modal');
                            } else {
                                handleResError(res);
                            }
                        },
                        error: function (res) {
                            let errorInfo = res.responseJSON.metadata.message || 'Ups.. Sedang terjadi kesalahan pada sistem.';
                            console.log(errorInfo)
                            resErrorSwal('Peringatan!', errorInfo, 'error');
                        }
                    });
                } else if (result.isDenied) {
                    resErrorSwal('Dibatalkan', 'Data tidak jadi dihapus.', 'warning')
                }
            });
        }

        function getRolesOption(selectedRoles = [], reset = false) {
            $.ajax({
                url: "/options/roles",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    let roleOptionsHtml = '';

                    if (result.response.length === 0 || result.response === null || result.response === []) {
                        roleOptionsHtml = `
                            <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                                <a href="/management-role" class="btn btn-secondary">
                                    Tambahkan Role Baru
                                </a>
                            </div>
                        `;
                    } else {
                        result.response.forEach(role => {
                            // Checkbox hanya akan tercentang jika `reset` adalah `false` dan role ada di `selectedRoles`
                            const isChecked = !reset && selectedRoles.includes(role.id) ? 'checked' : '';
                            roleOptionsHtml += `
                                <div class="d-flex fv-row mb-3">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input me-3"
                                            name="user_roles[]"
                                            id="role_option_${role.id}"
                                            type="checkbox"
                                            value="${role.id}"
                                            ${isChecked} />
                                        <label class="form-check-label" for="role_option_${role.id}">
                                            <div class="fw-bold text-gray-800">${role.role_name}</div>
                                            <div class="text-gray-600">${role.description || ''}</div>
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                    }

                    $('#role-options-container').html(roleOptionsHtml);
                },
                error: function(xhr) {
                    alertResultError(xhr.responseJSON, "Error loading Role Options");
                }
            });
        }

        function showPassword() {
            if (password == 'hide') {
                password = 'show';
                $('#password').attr('type', 'text');
                $('#icon-password').removeClass('fa-eye');
                $('#icon-password').addClass('fa-eye-slash');
            } else {
                password = 'hide';
                $('#password').attr('type', 'password');
                $('#icon-password').removeClass('fa-eye-slash');
                $('#icon-password').addClass('fa-eye');
            }
        }
    </script>
@endpush
