@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Product') --}}
@section('title', 'Product')
@section('product', 'here')

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
                                <th class="min-w-125px">Harga Produk</th>
                                <th class="min-w-125px">Status Produk</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="product_detail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <!-- Carousel Gambar -->
                        <div id="product_image_carousel" class="carousel slide w-50" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Gambar akan diinject dengan JavaScript -->
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
                            <h3 id="product_name" class="text-primary fw-bold"></h3>
                            <p id="product_description" class="text-muted"></p>
                            <ul class="list-unstyled">
                                <li><strong>Harga Dasar:</strong> <span id="base_price"></span></li>
                                <li><strong>Stok Total:</strong> <span id="total_stock"></span></li>
                                <li id="category_name_container"><strong>Kategori:</strong> <span id="category_name"></span></li>
                                <li id="brand_name_container"><strong>Brand:</strong> <span id="brand_name"></span></li>
                                <li><strong>Status:</strong> <span id="status"></span></li>
                            </ul>
    
                            <div id="product_variants">
                                <strong>Varian:</strong>
                                <ul class="list-unstyled mt-2">
                                    <!-- Varian akan diinject dengan JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    
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
    
    
    

@endsection

@push('js')
    <script>
        let table;

        $(document).ready(function() {
            // Datatable
            table = $('#table').DataTable({
                processing: true, serverSide: true,
                ajax: {
                    url: '{{ route("product.indexJson") }}',
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
                    { data: 'base_price',       orderable: true },
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
                    $('#base_price').text(data.base_price ? `Rp ${new Intl.NumberFormat('id-ID').format(data.base_price)}` : '-');
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
            window.location.href = "{{ url('product/edit') }}" + "/" + slug;
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
                        url: "{{ url('product/delete') }}" + "/" + slug,
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