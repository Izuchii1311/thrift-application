@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Category') --}}
@section('title', 'Category')
@section('category', 'here')

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
                            @if ($menu_access['can_export'])
                                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
                            @endif

                            {{-- Add New Data --}}
                            @if ($menu_access['can_create'])
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#form_modal" onclick="tambahData()">
                                <i class="ki-outline ki-plus fs-2"></i>Tambah Kategori Baru</button>
                            @endif
                        </div>

                        {{-- Export User --}}
                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                            {{-- Modal --}}
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    {{-- Modal Header --}}
                                    <div class="modal-header">
                                        <h2 class="fw-bold">Export Kategori</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close" onclick="$('#kt_modal_export_users').modal('hide');">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <form id="kt_modal_export_users_form" class="form" action="#">
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
                                        <h2 class="fw-bold" id="form_modal_title"></h2>
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
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Kategori</label>
                                                    <input type="text" name="category_name" id="category_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Kategori"/>
                                                </div>

                                                {{-- Input data --}}
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Deskripsi</label>
                                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Deskripsi"></textarea>
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
                                <th class="min-w-125px">Nama Kategori</th>
                                <th class="min-w-125px">Deskripsi</th>
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
        let canCreateMenu = {{ $menu_access['can_create'] ? 'true' : 'false' }};
        let canUpdateMenu = {{ $menu_access['can_update'] ? 'true' : 'false' }};

        $(document).ready(function() {
            // Datatable
            table = $('#table').DataTable({
                processing: true, serverSide: true,
                ajax: {
                    url: '{{ route("category.indexJson") }}',
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
                    { data: 'DT_RowIndex',      orderable: false },
                    { data: 'action',           orderable: false, className: 'text-center' },
                    { data: 'category_name',    orderable: true },
                    { data: 'description',      orderable: true },
                ],
                language: {
                    emptyTable: "Maaf, data saat ini belum tersedia.",
                    processing: "Memuat data, mohon tunggu..."
                },
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[ 2, "asc" ]]
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
            if (!canCreateMenu) {
                Swal.fire({
                    title: 'Tidak diizinkan!',
                    text: 'Anda tidak memiliki hak akses untuk menambah kategori.',
                    icon: 'error'
                });
                return;
            }

            $('#form-data').attr('action', '{{ route("category.store") }}');

            $('#form-data')[0].reset();
            $('#form-data select').val(null).trigger("change");

            $('#form_modal_title').text('Form Tambah Kategori Baru');
            $('#category_name, #description').val("");

            $('#form_modal').modal('show');
        }

        function editData(slug) {
            if (!canUpdateMenu) {
                Swal.fire({
                    title: 'Tidak diizinkan!',
                    text: 'Anda tidak memiliki hak akses untuk mengedit kategori.',
                    icon: 'error'
                });
                return;
            }

            $('#form-data').attr('action', "{{ url('category/update') }}" + "/" + slug);

            $('#form_modal_title').text('Edit Data Kategori');
            showLoading();

            $.ajax({
                url: "{{ url('category/detail/json') }}" + "/" + slug,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    const category = result.response;

                    $('#category_name').val(category.category_name);
                    $('#description').val(category.description);

                    $('#form_modal').modal('show');
                    Swal.close();
                },
                error: function(result) {
                    alertResultError(result, "Error, Data Kategori gagal didapatkan.");
                }
            });
        }

        function submitForm() {
            let formData = new FormData($('#form-data')[0]);

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

        function deleteData(slug) {
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
                        url: "{{ url('category/delete') }}" + "/" + slug,
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
    </script>
@endpush
