<form id="form-data" class="form d-flex flex-column flex-lg-row" action="" enctype="multipart/form-data">
    @csrf

    {{-- Side Form --}}
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        {{-- Status Produk --}}
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Status</h2>
                </div>
                <div class="card-toolbar d-hide">
                    <div class="rounded-circle bg-success w-15px h-15px"></div>
                </div>
            </div>

            <div class="card-body pt-0">
                <select class="form-select mb-2" name="status" id="status" data-control="select2" data-hide-search="true" data-placeholder="Status Produk">
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
                
                <div class="text-muted fs-7">Tetapkan Status Produk.</div>
            </div>
        </div>

        {{-- Category & Brands --}}
        <div class="card card-flush py-4">
            <div class="card-header">
                <div class="card-title">
                    <h2>Product Detail</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <label class="form-label">Kategori</label>
                <select class="form-select mb-2" name="category_id" id="category_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori" data-allow-clear="true">
                    {{-- Select 2 --}}
                </select>
                <div class="text-muted fs-7 mb-7">Kategori produk wajib dipilih.</div>

                <label class="form-label">Brand</label>
                <select class="form-select mb-2" name="brand_id" id="brand_id" data-control="select2" data-hide-search="true" data-placeholder="Pilih Brand" data-allow-clear="true">
                    {{-- Select 2 --}}
                </select>
                <div class="text-muted fs-7 mb-7">Brand produk wajib dipilih.</div>
            </div>
        </div>
    </div>

    {{-- Main Form --}}
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        {{-- Tabs --}}
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" data-bs-target="#data-umum" style="cursor: pointer;">Data Umum</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" data-bs-target="#tambahan" style="cursor: pointer;">Tambahan (Opsional)</a>
            </li>
        </ul>
        
        {{-- Content --}}
        <div class="tab-content">
            {{-- Data Umum --}}
            <div class="tab-pane fade show active" id="data-umum" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Umum</h2>
                            </div>
                        </div>

                        {{-- Nama & Deskripsi Produk --}}
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Nama Produk</label>
                                <input type="text" name="product_name" id="product_name" class="form-control mb-2" placeholder="Nama Produk"/>
                            </div>
                            <div >
                                <label class="required form-label">Deskripsi Produk</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Deskripsi Produk"></textarea>
                                <div class="text-muted fs-7">Jelaskan deskripsi produk secara detail.</div>
                            </div>
                        </div>
                    </div>

                    {{-- File Product --}}
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fs-3 fw-semibold text-gray-900">File / Gambar Produk</h2>
                            </div>
                        </div>
                    
                        <div class="card-body pt-0">
                            <div class="dropzone dz-clickable" id="product_images">
                                <div class="dz-message needsclick d-flex align-items-center justify-content-center flex-column py-5">
                                    <i class="ki-outline ki-file-up text-primary fs-3x mb-3"></i>
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-2">Simpan file disini untuk diupload.</h3>
                                    <span class="fs-6 fw-semibold text-gray-400">File yang diupload max: 10</span>
                                </div>
                            </div>
                            <div class="text-muted fs-7 mt-2">Upload gambar produk yang dibutuhkan.</div>
                        </div>
                    </div>

                    <!--begin::Pricing-->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Harga & Stock Produk</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-10 fv-row">
                                <label class="required form-label">Harga Produk (Base Price)</label>
                                <input type="text" name="base_price" id="base_price" class="form-control mb-2" placeholder="Harga Produk (Base Price)"/>
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="required form-label">Stock Produk</label>
                                <input type="text" name="total_stock" id="total_stock" class="form-control mb-2" placeholder="Stock Produk"/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Tambahan --}}
            <div class="tab-pane fade" id="tambahan" role="tab-panel">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Varian</h2>
                            </div>
                        </div>
            
                        <!-- Variant Produk -->
                        <div class="card-body pt-0">
                            <label class="form-label">Tambahkan Varian Produk</label>
                            <div id="variant_data">
                                <div class="variant-row">
                                    <div class="form-group d-flex flex-wrap align-items-center gap-5">
                                        <div class="w-100 w-md-200px">
                                            <select class="form-select type-variant" name="type_variant[]" data-control="select2" data-hide-search="true" data-placeholder="Pilih Tipe Varian">
                                                <option></option>
                                                <option value="warna">Warna</option>
                                                <option value="ukuran">Ukuran</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control mw-100 w-200px size-variant d-none" name="size_variant[]" placeholder="Ukuran" />
                                        <input type="text" class="form-control mw-100 w-200px color-variant d-none" name="color_variant[]" placeholder="Warna" />
                                        <input type="text" class="form-control mw-100 w-200px stock-quantity-size d-none" name="stock_quantity_size[]" id="stock_quantity_size" placeholder="Stock Ukuran" />
                                        <input type="text" class="form-control mw-100 w-200px stock-quantity-color d-none" name="stock_quantity_color[]" id="stock_quantity_color" placeholder="Stock Warna" />
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger delete-variant">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group mt-5">
                                <button type="button" id="add_variant" class="btn btn-sm btn-light-primary">
                                    <i class="ki-outline ki-plus fs-2"></i> Tambah Varian Produk Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('product.index') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Batal</a>
            <button type="button" class="btn btn-primary" id="btn-submit" onclick="submitForm()">
                <span class="indicator-label">Simpan</span>
            </button>
        </div>
    </div>
</form>