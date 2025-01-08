@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'List Product') --}}
@section('title', 'List Product')
@section('list-product', 'here')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            @include('layouts.dashboard_components.toolbar')

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card-body pt-0 d-flex flex-wrap align-items-end gap-3 mb-4 justify-content-end">
                        <button onclick="resetFilters()" type="button" class="ms-1 btn btn-secondary">
                            Reset
                        </button>
                        <!-- Kategori -->
                        <div>
                            <select class="form-select" name="category_id" id="category_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori">
                            </select>
                        </div>

                        <!-- Brand -->
                        <div>
                            <select class="form-select" name="brand_id" id="brand_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Brand">
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-solid" placeholder="Search" id="search" style="width: 250px;" />
                            <button onclick="search()" type="button" class="ms-1 btn btn-light-primary">
                                <i class="ki-outline ki-magnifier"></i>
                            </button>
                        </div>
                    </div>

                    <div id="loading-spinner" style="display:none;" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9" id="products-container">
                        @forelse ($products as $product)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <!-- Gambar Produk -->
                                    <img
                                        src="{{ $product->images->first()?->image_url ? asset('storage/' . $product->images->first()->image_url) : asset('assets/dashboard/media/no_image.jpg') }}"
                                        class="card-img-top"
                                        alt="{{ $product->product_name }}"
                                        style="height: 200px; object-fit: cover;"
                                    >
                                    <div class="card-body">
                                        <h5 class="card-title text-primary fw-bold">{{ $product->product_name }}</h5>
                                        <p class="card-text text-muted">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>
                                        @if($product->category)
                                            <p class="card-text">
                                                <strong>Kategori:</strong> {{ $product->category->category_name }}
                                            </p>
                                        @endif

                                        @if($product->brand)
                                            <p class="card-text">
                                                <strong>Brand:</strong> {{ $product->brand->brand_name }}
                                            </p>
                                        @endif
                                        <p class="card-text">
                                            <strong>Harga:</strong> Rp {{ $product->base_price }}
                                        </p>
                                        <p class="card-text">
                                            <strong>Stock:</strong> {{ $product->total_stock }}
                                        </p>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            onclick="loadDetailData('{{ $product->slug }}')"
                                            data-bs-toggle="modal"
                                            data-bs-target="#product_detail"
                                        >
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Handle jika produk tidak ada --}}
                            <div class="d-flex justify-content-center align-items-center" style="height: 200px; width: 100%;">
                                <p class="text-center text-muted">Belum ada produk, Produk masih menunggu dari Opname.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    {{-- Pagination --}}
                    @if($products)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif

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

                            <hr>

                            <form id="set_margin_price" action="">
                                @csrf
                                <div class="mb-3">
                                    <label for="margin_price" class="form-label">Margin harga jual</label>
                                    <input type="text" name="margin_price" id="margin_price" class="form-control mb-2" placeholder="Masukkan Harga Jual" required/>
                                    <div class="text-muted fs-7 mb-7 mt-2">Sebagai contoh Harga produk Rp. 200.000 maka Tetapkan margin harga jual, misal Rp.25.000. Hasilnya akan dikalkulasikan sehingga menjadi Rp.225.000</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Tetapkan Margin Harga Jual</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        Inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }).mask("#margin_price");

        $(document).ready(function() {
            getCategoryOption();
            getBrandOption();

            $('#category_id, #brand_id').on('change', function() {
                loadFilteredProducts();
            });

            $('#search').on('keyup', function() {
                loadFilteredProducts();
            });
        });

        function resetFilters() {
            $('#category_id').val('').trigger('change');
            $('#brand_id').val('').trigger('change');
            $('#search').val('');
            loadFilteredProducts();
            window.location.href = "{{ route('list-product.listProduct') }}";
        }

        function loadFilteredProducts() {
            const categoryId = $('#category_id').val();
            const brandId = $('#brand_id').val();
            const searchText = $('#search').val();

            $('#loading-spinner').show();

            $.ajax({
                url: "{{ route('list-product.listProduct') }}",
                method: 'GET',
                data: {
                    category_id: categoryId,
                    brand_id: brandId,
                    search: searchText
                },
                success: function(response) {
                    $('#loading-spinner').hide();
                    
                    const products = $(response).find('#products-container').html();
                    if (products.trim() === "") {
                        $('#products-container').html('');
                    } else {
                        $('#products-container').html(products);
                        $('.pagination').replaceWith($(response).find('.pagination'));
                    }
                },
                error: function(xhr) {
                    $('#loading-spinner').hide();
                }
            });
        }

        function loadDetailData(slug) {
            console.log(slug)
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
                    console.log(data)

                    // Setel data detail produk
                    $('#product_name').text(data.product_name || 'Nama Produk Tidak Diketahui');
                    $('#product_description').text(data.description || 'Deskripsi tidak tersedia');
                    $('#base_price').text(data.base_price ? `Rp ${data.base_price}` : '-');
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

                    $('#set_margin_price').data('slug', slug);

                    Swal.close();
                    $('#product_detail').modal('show');
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Data Produk gagal didapatkan.', 'error');
                },
            });
        }

        function getCategoryOption() {
            $.ajax({
                url: "/options/categories",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    $('#category_id').empty().append('<option></option>');
                    result.response.forEach(item => {
                        $("#category_id").append('<option value="' + item.id + '">' + item.category_name + '</option>');
                    });
                },
                error: function(result) {
                    alertResultError(xhr.responseJSON, "Error loading Category Options");
                }
            });
        }
    
        function getBrandOption() {
            $.ajax({
                url: "/options/brands",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    $('#brand_id').empty().append('<option></option>');
                    result.response.forEach(item => {
                        $("#brand_id").append('<option value="' + item.id + '">' + item.brand_name + '</option>');
                    });
                },
                error: function(result) {
                    alertResultError(xhr.responseJSON, "Error loading Brand Options");
                }
            });
        }

        $('#set_margin_price').on('submit', function (e) {
            e.preventDefault();

            const marginPrice = $('#margin_price').val();
            const productSlug = $('#set_margin_price').data('slug');

            if (!marginPrice) {
                Swal.fire('Error', 'Margin harga jual harus diisi!', 'error');
                return;
            }

            Swal.fire({
                title: 'Simpan?',
                text: "Pastikan data yang anda isi telah sesuai!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/product/update-price') }}/${productSlug}`,
                        method: 'POST',
                        data: {
                            margin_price: marginPrice,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil memperbarui data.', '#product_detail', '/list-product');
                            } else {
                                handleResError(res);
                            }
                        },
                        error: function (res) {
                            let errorInfo = res.responseJSON.metadata.message || 'Ups.. Sedang terjadi kesalahan pada sistem.';
                            resErrorSwal('Peringatan!', errorInfo, 'error');
                        }
                    });
                } else {
                    resErrorSwal('Dibatalkan', 'Periksa kembali data yang anda isi.', 'warning');
                }
            });
        });

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
