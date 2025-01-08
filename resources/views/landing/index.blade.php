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
                            @if (Auth::user())
                                <a href="{{ route('login') }}">
                                    <i class="ki-outline ki-user fs-2"></i>
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="btn btn-success">Login</a>
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
        
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="products-container">
                @forelse ($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                            <!-- Gambar Produk -->
                            <div class="position-relative">
                                <img
                                    src="{{ $product->images->first()?->image_url ? asset('storage/' . $product->images->first()->image_url) : asset('assets/dashboard/media/no_image.jpg') }}"
                                    class="card-img-top"
                                    alt="{{ $product->product_name }}"
                                    style="width: 100%; height: 300px; object-fit: cover;"
                                >
                            </div>
                            <!-- Informasi Produk -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary fw-bold mb-2">{{ $product->product_name }}</h5>
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
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Handle jika produk tidak ada --}}
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada produk, Produk masih menunggu dari Opname.</p>
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