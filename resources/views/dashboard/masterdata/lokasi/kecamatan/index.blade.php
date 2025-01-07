@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Masterdata Kecamatan') --}}
@section('title', 'Masterdata Kecamatan')
@section('masterdata-kecamatan', 'here')

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
                            {{-- Export --}}
                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                            <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
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
                    </div>
                </div>

                {{-- Body --}}
                <div class="card-body py-4">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th class="text-center">Kode / Nama Kota</th>
                                <th class="text-center">Kode Kecamatan</th>
                                <th class="min-w-125px">Nama Kecamatan</th>
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
            // Datatable
            table = $('#table').DataTable({
                processing: true, serverSide: true,
                ajax: {
                    url: '{{ route("masterdata-kecamatan.indexJson") }}',
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
                    { data: 'kota_info',        orderable: true, className: 'text-center' },
                    { data: 'id',               orderable: true, className: 'text-center' },
                    { data: 'nama_kecamatan',   orderable: true },
                ],
                language: {
                    emptyTable: "Maaf, data saat ini belum tersedia.",
                    processing: "Memuat data, mohon tunggu..."
                },
                lengthMenu: [[50, 75, 100, 200], [50, 75, 100, 200]],
                order: [[ 1, "asc" ]]
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
    </script>
@endpush
