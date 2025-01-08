@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Katalog Product') --}}
@section('title', 'Katalog Product')
@section('katalog-product', 'here')

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
                        <div class="d-flex justify-content-end">
                            @if ($menu_access['can_export'])
                                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#export_data">
                                <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
                            @endif

                            {{-- Add New Data --}}
                            @if ($menu_access['can_create'])
                            <a href="{{ route("product.create") }}">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal">
                                <i class="ki-outline ki-plus fs-2"></i>Tambah Produk Baru</button>
                            </a>
                            @endif
                        </div>

                        {{-- Export Produk --}}
                        <div class="modal fade" id="export_data" tabindex="-1" aria-hidden="true">
                            {{-- Modal --}}
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    {{-- Modal Header --}}
                                    <div class="modal-header">
                                        <h2 class="fw-bold">Export Produk</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close" onclick="$('#export_data').modal('hide');">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </div>
                                    </div>

                                    {{-- Modal Body --}}
                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <form id="export_data_form" class="form" action="#">
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
                                <th class="text-center">Aksi</th>
                                <th class="min-w-125px">Informasi Produk</th>
                                <th class="min-w-125px">Kategori</th>
                                <th class="min-w-125px">Brand</th>
                                <th class="min-w-125px">Total Stock</th>
                                <th class="min-w-125px">Margin Produk</th>
                                <th class="min-w-125px">Harga Produk</th>
                                <th class="min-w-125px">Status Produk</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="product_detail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <!-- Gambar Produk -->
                        <div id="product_image_carousel" class="carousel slide w-50 rounded" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Gambar akan diinject oleh JavaScript -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#product_image_carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#product_image_carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <!-- Detail Produk -->
                        <div class="ms-4 w-50">
                            <h4 id="product_name" class="text-primary fw-bold">Nama Produk</h4>
                            <p id="product_description" class="text-muted mb-4">Deskripsi Produk</p>

                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-tag text-success me-2"></i><strong>Harga:</strong> <span id="base_price">-</span></li>
                                <li class="mb-2"><i class="fas fa-tag text-success me-2"></i><strong>Margin Produk:</strong> <span id="margin_price">-</span></li>
                                <li class="mb-2"><i class="fas fa-tag text-success me-2"></i><strong>Total Harga Produk:</strong> <span id="final_price">-</span></li>
                                <li class="mb-2"><i class="fas fa-cubes text-warning me-2"></i><strong>Stock:</strong> <span id="total_stock">-</span></li>
                                <li class="mb-2" id="category_name_container"><i class="fas fa-layer-group text-primary me-2"></i><strong>Kategori:</strong> <span id="category_name">-</span></li>
                                <li class="mb-2" id="brand_name_container"><i class="fas fa-industry text-info me-2"></i><strong>Brand:</strong> <span id="brand_name">-</span></li>
                                <li class="mb-2"><i class="fas fa-info-circle text-secondary me-2"></i><strong>Status:</strong> <span id="status">-</span></li>
                            </ul>

                            <div id="product_variants" class="mt-3">
                                <h5><i class="fas fa-th-large text-primary me-2"></i>Varian:</h5>
                                <ul class="list-unstyled mt-2">
                                    <!-- Varian akan diinject oleh JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="form_modal" tabindex="-1" aria-hidden="true">
        {{-- Modal --}}
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                {{-- Modal Header --}}
                <div class="modal-header" id="form_modal_header">
                    <h2 class="fw-bold" id="form_modal_title">Edit Produk</h2>
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
                                <label class="required fw-semibold fs-6 mb-2">Status Produk</label>
                                
                                <select class="form-select mb-2" name="status" id="status_update" data-control="select2" data-hide-search="true" data-placeholder="Status Produk">
                                    <option></option>
                                    @if(auth()->user()->roles->firstWhere('pivot.is_active', true)->role_name === 'opname')
                                        <!-- Opsi untuk Opname -->
                                        <option value="draft">Draft</option>
                                        <option value="proses">Dalam Proses</option>
                                        <option value="menunggu_validasi">Menunggu Validasi</option>
                                        <option value="tidak_layak">Tidak Layak</option>
                                    @elseif(auth()->user()->roles->firstWhere('pivot.is_active', true)->role_name === 'admin')
                                        <!-- Opsi untuk Admin -->
                                        <option value="tersedia">Tersedia</option>
                                        <option value="tidak_tersedia">Tidak Tersedia</option>
                                    @else 
                                        <option value="draft">Draft</option>
                                        <option value="proses">Dalam Proses</option>
                                        <option value="menunggu_validasi">Menunggu Validasi</option>
                                        <option value="tidak_layak">Tidak Layak</option>
                                        <option value="tersedia">Tersedia</option>
                                        <option value="tidak_tersedia">Tidak Tersedia</option>
                                    @endif
                                </select>
                                <div class="text-muted fs-7">Ubah Status Produk.</div>
                            </div>

                            {{-- Input data --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Ubah Harga Jual</label>
                                <input type="text" name="margin_price" id="margin_price_update" class="form-control mb-2" placeholder="Masukkan Harga Jual" required/>
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
@endsection

@push('js')
    <script>
        let table;

        $(document).ready(function() {
            // Datatable
            table = $('#table').DataTable({
                processing: true, serverSide: true,
                ajax: {
                    url: '{{ route("katalog-product.indexKatalogJson") }}',
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
                    { data: 'product_info',     orderable: false },
                    { data: 'category_name',    orderable: true },
                    { data: 'brand_name',       orderable: true, render: function (data) { return data ?? '-'; } },
                    { data: 'total_stock',      orderable: true },
                    { data: 'margin_price',     orderable: true },
                    { data: 'final_price',      orderable: true },
                    { data: 'status',           orderable: true },
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

        function loadDetailData(slug) {
            Swal.fire({
                title: 'Loading Data',
                text: 'Silahkan tunggu, sedang memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            $.ajax({
                url: `{{ url('/product/detail/json') }}/${slug}`,
                method: 'GET',
                success: function (result) {
                    let data = result.response;

                    // Setel data detail produk
                    $('#product_name').text(data.product_name || 'Nama Produk Tidak Diketahui');
                    $('#product_description').text(data.description || 'Deskripsi tidak tersedia');
                    $('#base_price').text(data.base_price ? `Rp ${data.base_price}` : '-');
                    $('#margin_price').text(data.margin_price ? `Rp ${data.margin_price}` : '-');
                    $('#final_price').text(data.final_price ? `Rp ${data.final_price}` : '-');
                    $('#total_stock').text(data.total_stock || '-');

                    const status = data.status || '-';
                    const formattedStatus = status.replace(/_/g, ' ').toLowerCase().replace(/^./, status[0].toUpperCase());

                    const badgeClasses = {
                        'tersedia': 'badge-success',
                        'tidak_tersedia': 'badge-danger',
                        'proses': 'badge-warning',
                        'menunggu_validasi': 'badge-info',
                        'tidak_layak': 'badge-secondary',
                        'draft': 'badge-primary'
                    };

                    const badgeClass = badgeClasses[status] || 'badge-secondary';

                    $('#status').html(`<span class="badge ${badgeClass}">${formattedStatus}</span>`);

                    const categoryName = data.category ? data.category.category_name : '-';
                    $('#category_name_container').toggle(!!data.category_id);
                    $('#category_name').text(`${categoryName}`);

                    // Tampilkan brand
                    const brandName = data.brand ? data.brand.brand_name : '-';
                    $('#brand_name_container').toggle(!!data.brand_id);
                    $('#brand_name').text(`${brandName}`);

                    // Setel gambar carousel
                    let carouselInner = $('#product_image_carousel .carousel-inner');
                    carouselInner.empty();
                    if (data.images && data.images.length > 0) {
                        data.images.forEach((image, index) => {
                            carouselInner.append(`
                                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                    <img src="{{ asset('storage') }}/${image.image_url}" class="d-block w-100 rounded" alt="Product Image">
                                </div>
                            `);
                        });
                    } else {
                        carouselInner.append(`
                            <div class="carousel-item active">
                                <img src="{{ asset('assets/dashboard/media/no_image.jpg') }}" class="d-block w-100 rounded" alt="No Image">
                            </div>
                        `);
                    }

                    // Setel varian produk
                    let variantsContainer = $('#product_variants ul');
                    variantsContainer.empty();
                    if (data.variants && data.variants.length > 0) {
                        data.variants.forEach(variant => {
                            variantsContainer.append(`
                                <li class="mb-1"><i class="fas fa-palette text-primary me-2"></i>
                                    ${variant.type_variant === 'warna' ? `Warna: ${variant.color}` : ''}
                                    ${variant.type_variant === 'ukuran' ? `Ukuran: ${variant.size}` : ''}
                                    <span class="text-muted">(Stok: ${variant.stock_quantity})</span>
                                </li>
                            `);
                        });
                    } else {
                        variantsContainer.append('<li class="text-muted">Tidak ada varian tersedia.</li>');
                    }

                    Swal.close();
                    $('#product_detail').modal('show');
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Data Produk gagal didapatkan.', 'error');
                },
            });
        }

        function editData(slug) {
            $('#form-data').attr('action', "{{ url('katalog-product/update') }}" + "/" + slug);

            $('#form_modal_title').text('Edit Data Produk');
            showLoading();

            $.ajax({
                url: `{{ url('/product/detail/json') }}/${slug}`,
                type: 'GET',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    const data = result.response;

                    $('#margin_price_update').val(data.margin_price);
                    if ($('#status_update option[value="' + data.status+ '"]').length > 0) {
                        $('#status_update').val(data.status).trigger('change');
                    }

                    $('#form_modal').modal('show');
                    Swal.close();
                },
                error: function(result) {
                    alertResultError(result, "Error, Data Product gagal didapatkan.");
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
    </script>
@endpush

@push('css')
    <style>
        #product_image_carousel .carousel-item img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            object-position: center;
        }
    </style>
@endpush
