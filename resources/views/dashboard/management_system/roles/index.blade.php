@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Management Role') --}}
@section('title', 'Management Role')
@section('management-role', 'here')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            @include('layouts.dashboard_components.toolbar')

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
                        @if($hasRoles)
                            @foreach($roles as $role)
                                <div class="col-md-4 mb-4">
                                    <div class="card card-flush h-md-100">
                                        {{-- Header --}}
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h2 class="card-title">{{ $role->display_name }}<span class="badge {{ $role->is_active ? 'badge-light-success' : 'badge-light-danger' }} ms-2">{{ $role->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></h2>
                                            <i class="ki-outline ki-trash text-danger fs-1" onclick="deleteData('{{ encrypt($role->id) }}')"></i>
                                        </div>

                                        {{-- Body --}}
                                        <div class="card-body pt-1">
                                            <div class="fw-bold text-gray-600 mb-5">
                                                Total User dengan role ini: {{ $role->users->count() }}
                                            </div>
                                            <div class="d-flex flex-column text-gray-600">
                                                @foreach($role->permissions->take(5) as $permission)
                                                    <div class="d-flex align-items-center py-2">
                                                        <span class="bullet bg-primary me-3"></span>
                                                        {{ $permission->permission_name }}
                                                    </div>
                                                @endforeach

                                                @if($role->permissions->count() > 5)
                                                    <div class="d-flex align-items-center py-2">
                                                        <span class="bullet bg-primary me-3"></span>
                                                        <em>dan {{ $role->permissions->count() - 5 }} lainnya...</em>
                                                    </div>
                                                @elseif ($role->permissions->count() == 0)
                                                    <div class="d-flex align-items-center py-2">
                                                        <span class="bullet bg-primary me-3"></span>
                                                        <em>Tidak ada permission yang diizinkan</em>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="card-footer flex-wrap pt-0">
                                            <a
                                            {{-- href="{{ route('roles.show', encrypt($role->id)) }}"  --}}
                                            class="btn btn-light btn-active-primary my-1 me-2">
                                                View Role
                                            </a>
                                            <button type="button" class="btn btn-light btn-active-light-primary my-1"
                                                    data-bs-toggle="modal" data-bs-target="#form_modal"
                                                    onclick="editData('{{ encrypt($role->id) }}')">
                                                Edit Role
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Handle if data role null --}}
                        @endif

                        {{-- Card create new Role --}}
                        <div class="col-md-4">
                            <div class="card h-md-100">
                                <div class="card-body d-flex flex-center">
                                    <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal" data-bs-target="#form_modal" onclick="tambahData()">
                                        <img src="{{ asset('assets/dashboard/media/illustrations/sketchy-1/4.png') }}" alt="" class="mw-100 mh-150px mb-7" />
                                        <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Tambah Role Baru</div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Create New Role --}}
                    <div class="modal fade" id="form_modal" tabindex="-1" aria-hidden="true">
                        {{-- Modal --}}
                        <div class="modal-dialog modal-dialog-centered mw-750px">
                            <div class="modal-content">
                                {{-- Modal Header --}}
                                <div class="modal-header">
                                    <h2 class="fw-bold">Add a Role</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="$('#form_modal').modal('hide');">
                                        <i class="ki-outline ki-cross fs-1"></i>
                                    </div>
                                </div>

                                {{-- Modal Body --}}
                                <div class="modal-body px-5 my-7">
                                    <form id="form-data" class="form" action="">
                                        @csrf

                                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="form_modal_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#form_modal_header" data-kt-scroll-wrappers="#form_modal_scroll" data-kt-scroll-offset="300px" >
                                            {{-- Input data --}}
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Role</label>
                                                <input type="text" name="role_name" id="role_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Role"/>
                                                <div class="text-muted mt-2">Role yang akan dibuat akan memiliki hak akses pada halaman tertentu yang dapat dilihat pada menu 'Menu dan Hak Akses'.</div>
                                            </div>

                                            {{-- Input data --}}
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Display Name</label>
                                                <input type="text" name="display_name" id="display_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Display Name"/>
                                                <div class="text-muted mt-2">Display name akan digunakan untuk menampilkan nama role.</div>
                                            </div>

                                            {{-- Input data --}}
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Deskripsi</label>
                                                <textarea name="description" id="description" cols="30" rows="10" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Deskripsi"></textarea>
                                            </div>

                                            {{-- Input data --}}
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Status Role</label>
                                                  <select class="form-select mb-2" name="is_active" data-control="select2" data-hide-search="true" data-placeholder="Status Role" id="is_active">
                                                    <option ></option>
                                                    <option value="true" selected="selected">Aktif</option>
                                                    <option value="false">Tidak Aktif</option>
                                                </select>
                                            </div>

                                            {{-- Input data --}}
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tipe Role</label>
                                                <input type="text" name="type_role" id="type_role" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tipe Role"/>
                                                <div class="text-muted mt-2">Tipe Role untuk mengelompokkan role ke dalam tipe tertentu.</div>
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

                    {{-- Pagination --}}
                    @if($hasRoles)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $roles->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function tambahData() {
            $('#form-data').attr('action', '{{ route("management-role.store") }}');

            $('#form-data')[0].reset();
            $('#form-data select').val(null).trigger('change');

            $('#form_modal_title').text('Form Tambah Role Baru');
            $('#role_name, #display_name, #description, #is_active, #type_role').val("");

            $('#form_modal').modal('show');
        }

        function editData(encryptedId) {
            $('#form-data').attr('action', "{{ url('management-role/update') }}" + "/" + encryptedId);

            $('#form_modal_title').text('Edit Data Role');
            showLoading();

            $.ajax({
                url: "{{ url('management-role/detail/json') }}" + "/" + encryptedId,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    const role = result.response;

                    $('#role_name').val(role.role_name);
                    $('#display_name').val(role.display_name);
                    $('#description').val(role.description);
                    $('#type_role').val(role.type_role);

                    if ($('#is_active option[value="' + String(role.is_active) + '"]').length > 0) {
                        $('#is_active').val(String(role.is_active)).trigger('change');
                    }

                    $('#form_modal').modal('show');
                    Swal.close();
                },
                error: function(result) {
                    alertResultError(result, "Error, Data Role gagal didapatkan.");
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
                                resSuccessSwal(res.metadata.message ?? 'Berhasil menambahkan data.', '', '/management-role');
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
                        url: "{{ url('management-role/delete') }}" + "/" + encryptedId,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil menghapus data.', '', '/management-role');
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
    </script>
@endpush

