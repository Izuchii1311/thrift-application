@extends('layouts.landing_layout')

@section('content')
    <div class="mb-0" id="home">
        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg" style="background-image: url({{ asset('assets/dashboard//media/svg/illustrations/landing.svg') }})">
            {{-- Header --}}
            <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                <div class="container">
                    <div class="d-flex align-items-center justify-content-between">
                        {{-- Logo --}}
                        <div class="d-flex align-items-center flex-equal">
                            <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                                <i class="ki-outline ki-abstract-14 fs-2hx"></i>
                            </button>
                            <a href="{{ route('landing-index') }}">
                                <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/landing.svg') }}" class="logo-default h-25px h-lg-30px" />
                                <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/landing-dark.svg') }}" class="logo-sticky h-20px h-lg-25px" />
                            </a>
                        </div>
                        
                        {{-- Wrapper --}}
                        <div class="d-lg-block" id="kt_header_nav_wrapper">
                            <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">

                                {{-- Menu --}}
                                <div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">
                                    {{-- Menu Item --}}
                                    <div class="menu-item">
                                        <a class="menu-link nav-link active py-3 px-4 px-xxl-6" href="{{ route('landing-index') }}">Home</a>
                                    </div>
                                    {{-- Menu Item --}}
                                    <div class="menu-item">
                                        <a class="menu-link nav-link active py-3 px-4 px-xxl-6" href="{{ route('landing-index') }}">Kategori</a>
                                    </div>
                                    {{-- Menu Item --}}
                                    <div class="menu-item">
                                        <a class="menu-link nav-link active py-3 px-4 px-xxl-6" href="{{ route('landing-index') }}">About</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div class="flex-equal text-end ms-1">
                            <div class="d-flex justify-content-end align-items-center ms-1">
                                @if (Auth::check())
                                    @php
                                        $user_info = Auth::user()->userRoleActiveInfo()['role_active_as'];
                                        $rule_role = \App\Models\ManagementSystem\Role::where('role_name', 'Customer')->orWhere('type_role', 'customer')->first();
                                    @endphp
                        
                                    @if ($user_info === 'customer' || $user_info === optional($rule_role)->name)
                                        <a href="{{ route('profileView') }}" class="me-3 text-decoration-none">
                                            <i class="ki-outline ki-user fs-1 text-info"></i>
                                        </a>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logout-form">
                                            @csrf
                                            <button type="button" class="btn btn-danger" id="logout-btn">Logout</button>
                                        </form>
                                    @else
                                        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Dashboard</a>
                                    @endif
                                @else
                                    <a href="{{ route('login_view') }}" class="btn btn-success">Login</a>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            {{-- Hero Content --}}
            <div class="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9">
                <div class="text-center mb-5 mb-lg-10 py-10 py-lg-20">
                    <h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-15">Toko Thrift Dengan Berbagai Macam <br />
                    <span style="background: linear-gradient(to right, #12CE5D 0%, #FFD80C 100%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
                        <span id="kt_landing_hero_text">Brand</span>
                    </span></h1>
                </div>
            </div>
        </div>
        
        <div class="landing-curve landing-dark-color mb-10 mb-lg-20">
            <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            {{-- Filter Section --}}
            @if($products && $products->count() > 0)
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
            @endif

            {{-- Loading Spinner --}}
            <div id="loading-spinner" style="display:none;" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            {{-- Product Container --}}
            @if($products && $products->count() > 0)
                <div id="products-wrapper">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="products-container">
                        @foreach ($products as $product)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                                    <!-- Gambar Produk -->
                                    <div class="position-relative">
                                        <img onclick="loadDetailData('{{ $product->slug }}')"
                                            src="{{ $product->images->first()?->image_url ? asset('storage/' . $product->images->first()->image_url) : asset('assets/dashboard/media/no_image.jpg') }}"
                                            class="card-img-top"
                                            alt="{{ $product->product_name }}"
                                            style="width: 100%; height: 300px; object-fit: cover;"
                                        >
                                    </div>
                                    <!-- Informasi Produk -->
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-primary fw-bold mb-2" onclick="loadDetailData('{{ $product->slug }}')">{{ $product->product_name }}</h5>
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>
                                        @if($product->category)
                                            <p class="card-text small">
                                                <strong>Kategori:</strong> {{ $product->category->category_name }}
                                            </p>
                                        @endif
                                        @if($product->brand)
                                            <p class="card-text small">
                                                <strong>Brand:</strong> {{ $product->brand->brand_name }}
                                            </p>
                                        @endif
                                        <p class="card-text small">
                                            <strong>Harga:</strong> Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                        </p>
                                        <p class="card-text small">
                                            <strong>Stock:</strong> {{ $product->total_stock }}
                                        </p>
                                        <div class="mt-auto">
                                            <button
                                                type="button"
                                                class="btn btn-primary w-100"
                                                onclick="loadDetailData('{{ $product->slug }}')"
                                                data-bs-toggle="modal"
                                                data-bs-target="#product_detail"
                                            >
                                                Pesan Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <div class="col-12 d-flex align-items-center justify-content-center" style="height: 400px;">
                    <p class="text-muted text-center">Ups... Maaf saat ini produk belum tersedia silahkan tunggu dan jangan lupa buka aplikasi berkala agar tidak tertinggal informasi dari kami.</p>
                </div>
            @endif


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
    
                            <!-- Variants Section -->
                            <div id="product_variants" class="mt-3">
                                <h5><i class="fas fa-th-large text-primary me-2"></i>Varian:</h5>
                                <div class="variants-container mt-2">
                                    <!-- Varian akan diinject oleh JavaScript -->
                                </div>
                            </div>
    
                            <!-- Jumlah Pembelian -->
                            <div id="purchase_quantity_container" class="mt-3 d-none">
                                <h5><i class="fas fa-shopping-cart text-primary me-2"></i>Jumlah Pembelian:</h5>
                                <input type="number" id="purchase_quantity" class="form-control w-25" min="1" value="1">
                            </div>
    
                            <!-- Beli Button -->
                            <div id="purchase_button_container" class="mt-3 d-none">
                                <button class="btn btn-primary" id="purchase_button">Beli</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('landing_js')
    <script>
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
            // window.location.href = "{{ route('landing-index') }}";
        }

        // Showing Product
        function loadFilteredProducts() {
            const categoryId = $('#category_id').val();
            const brandId = $('#brand_id').val();
            const searchText = $('#search').val();

            $('#loading-spinner').show();

            $.ajax({
                url: "{{ route('landing-index') }}",
                method: 'GET',
                data: {
                    category_id: categoryId,
                    brand_id: brandId,
                    search: searchText
                },
                success: function(response) {
                    $('#loading-spinner').hide();

                    // Ambil elemen produk dan pagination dari respons
                    const productContainer = $(response).find('#products-container');
                    const pagination = $(response).find('.pagination');

                    if (productContainer.length > 0 && productContainer.html().trim() !== "") {
                        // Jika data ditemukan, tampilkan produk dan pagination
                        $('#products-wrapper').html(`
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="products-container">
                                ${productContainer.html()}
                            </div>
                        `);
                        $('.pagination').html(pagination.html());
                    } else {
                        // Jika data kosong, tampilkan pesan
                        $('#products-wrapper').html(`
                            <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px;">
                                <p class="text-muted text-center">Tidak ada produk.</p>
                            </div>
                        `);
                        $('.pagination').html('');
                    }
                },
                error: function(xhr) {
                    $('#loading-spinner').hide();
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                }
            });
        }

        let selectedVariantId = null;
        let selectedVariantType = null;
        let productSlug = null;

        function loadDetailData(slug) {
            selectedVariantId   = null;
            selectedVariantType = null;
            productSlug = slug;
            $.ajax({
                url: `{{ url('/product/detail/json') }}/${slug}`,
                method: 'GET',
                success: function (result) {
                    let data = result.response;

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

                    // Handle Variants
                    let variantsContainer = $('#product_variants .variants-container');
                    variantsContainer.empty();
                    if (data.variants && data.variants.length > 0) {
                        data.variants.forEach(variant => {
                            let variantItem = `
                                <div class="badge bg-secondary me-2 mb-2 variant-badge" 
                                    data-variant-id="${variant.id}" 
                                    data-type="${variant.type_variant}" 
                                    data-color="${variant.color || ''}" 
                                    data-size="${variant.size || ''}" 
                                    data-stock="${variant.stock_quantity || 0}">
                                    
                                    ${variant.type_variant === 'warna' ? `${variant.color}` : ''}
                                    ${variant.type_variant === 'ukuran' ? `${variant.size}` : ''}
                                    ( ${variant.stock_quantity} )
                                </div>
                            `;
                            variantsContainer.append(variantItem);
                        });

                        // Variants click handler
                        $('.variant-badge').on('click', function() {
                            const selectedVariant = $(this);
                            $('.variant-badge').removeClass('bg-primary').addClass('bg-secondary');
                            selectedVariant.removeClass('bg-secondary').addClass('bg-primary');

                            selectedVariantId = selectedVariant.data('variant-id');
                            selectedVariantType = selectedVariant.data('type');
                        });
                    } else {
                        variantsContainer.append('<span class="text-muted">Tidak ada varian tersedia.</span>');
                    }

                    $('#purchase_quantity_container').removeClass('d-none');
                    $('#purchase_button_container').removeClass('d-none');

                    Swal.close();
                    $('#product_detail').modal('show');
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Data Produk gagal didapatkan.', 'error');
                },
            });
        }

        $('.variant-badge').on('click', function () {
            const selectedVariant = $(this);
            $('.variant-badge').removeClass('bg-primary').addClass('bg-secondary');
            selectedVariant.removeClass('bg-secondary').addClass('bg-primary');

            selectedVariantId = selectedVariant.data('variant-id');

            $('#purchase_quantity_container').removeClass('d-none');
            $('#purchase_button_container').removeClass('d-none');
        });

        // Handle Purchase Button
        $('#purchase_button').on('click', function () {
            const quantity = $('#purchase_quantity').val();

            if (!selectedVariantId) {
                Swal.fire('Error', 'Pilih varian terlebih dahulu', 'error');
                console.error('User belum memilih varian');
                return;
            }

            if (!quantity || quantity <= 0) {
                Swal.fire('Error', 'Jumlah pembelian tidak valid', 'error');
                console.error('Jumlah pembelian tidak valid:', quantity);
                return;
            }

            if (!productSlug) {
                Swal.fire('Error', 'Produk ID tidak ditemukan', 'error');
                console.error('Produk ID tidak ditemukan');
                return;
            }

            console.log(productSlug);
            console.log(selectedVariantId);
            console.log(quantity)

            $.ajax({
                url: '/payment',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_slug: productSlug,
                    variant_id: selectedVariantId,
                    amount: quantity,
                },
                success: function (response) {
                    // Berhasil
                    console.log('Pembayaran berhasil:', response);
                    Swal.fire('Success', 'Produk berhasil dibeli.', 'success')
                        .then(() => {
                            window.location.href = response.redirect_url || '/';
                        });
                },
                error: function (xhr, status, error) {
                    // Gagal
                    console.error('Error saat melakukan pembayaran:', xhr.responseJSON || error);
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                },
            });
        });


        // Data Option
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
    </script>
@endpush

@push('landing_css')
    <style>
        /* Memperbesar ukuran badge variant */
        .variant-badge {
            font-size: 14px;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Efek hover saat mouse di atas badge */
        .variant-badge:hover {
            background-color: #0056b3;
        }

        /* Menandai badge yang dipilih */
        .variant-badge.bg-primary {
            background-color: #007bff;
            color: white;
        }
    </style>
@endpush