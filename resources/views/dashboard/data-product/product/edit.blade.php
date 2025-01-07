@extends('layouts.dashboard_layout')
{{-- @section('title', $menu_path ? $menu_path->menu_name : 'Product') --}}
@section('title', 'Product Edit')
@section('product', 'here')

@section('content')
    @include('layouts.dashboard_components.toolbar')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    @include('dashboard.data-product.product.form')
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        var myDropzone;
        Inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }).mask("#base_price, #total_stock, #stock_quantity_size, #stock_quantity_color");

        $(document).ready(function () {
            loadDetailData();
            getCategoryOption();
            getBrandOption();

            // Dropzone Theme
            let themeMode = $('html').attr('data-bs-theme') || 'light';
            applyDropzoneTheme(themeMode, '#product_images');

            const observer = new MutationObserver(function () {
                let updatedTheme = $('html').attr('data-bs-theme') || 'light';
                applyDropzoneTheme(updatedTheme, '#product_images');
            });

            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });

            Dropzone.autoDiscover = false;

            // Define Dropzone
            myDropzone = new Dropzone("#product_images", {
                url: "#",
                paramName: "product_images[]",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 2,
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                dictMaxFilesExceeded: "Anda hanya dapat mengunggah hingga 10 file.",
                maxfilesexceeded: function (file) {
                    this.removeFile(file);
                },
            });

            $(document).on("change", ".type-variant", function () {
                const $row = $(this).closest(".variant-row");
                const $sizeVariant = $row.find(".size-variant");
                const $colorVariant = $row.find(".color-variant");
                const $stockQuantitySize = $row.find(".stock-quantity-size");
                const $stockQuantityColor = $row.find(".stock-quantity-color");

                // Reset the input fields
                $sizeVariant.val('');
                $colorVariant.val('');
                $stockQuantitySize.val('');
                $stockQuantityColor.val('');

                if ($(this).val() === "warna") {
                    // Show color-related inputs
                    $sizeVariant.addClass("d-none");
                    $stockQuantitySize.addClass("d-none");
                    $colorVariant.removeClass("d-none");
                    $stockQuantityColor.removeClass("d-none");
                } else if ($(this).val() === "ukuran") {
                    // Show size-related inputs
                    $colorVariant.addClass("d-none");
                    $stockQuantityColor.addClass("d-none");
                    $sizeVariant.removeClass("d-none");
                    $stockQuantitySize.removeClass("d-none");
                } else {
                    // Hide all inputs
                    $sizeVariant.addClass("d-none");
                    $colorVariant.addClass("d-none");
                    $stockQuantitySize.addClass("d-none");
                    $stockQuantityColor.addClass("d-none");
                }
            });

            // Add new variant row
            $("#add_variant").on("click", function () {
                const newVariantIndex = $(".variant-row").length;

                const newVariant = `
                    <div class="variant-row mt-2" style="display: none; opacity: 0;">
                        <div class="form-group d-flex flex-wrap align-items-center gap-5">
                            <div class="w-100 w-md-200px">
                                <select class="form-select type-variant" name="type_variant[${newVariantIndex}]" data-control="select2" data-hide-search="true" data-placeholder="Pilih Tipe Varian">
                                    <option></option>
                                    <option value="warna">Warna</option>
                                    <option value="ukuran">Ukuran</option>
                                </select>
                            </div>
                            <input type="text" class="form-control mw-100 w-200px size-variant d-none" name="size_variant[${newVariantIndex}]" placeholder="Ukuran" />
                            <input type="text" class="form-control mw-100 w-200px color-variant d-none" name="color_variant[${newVariantIndex}]" placeholder="Warna" />
                            <input type="number" class="form-control mw-100 w-200px stock-quantity-size d-none" name="stock_quantity_size[${newVariantIndex}]" placeholder="Stock Ukuran" />
                            <input type="number" class="form-control mw-100 w-200px stock-quantity-color d-none" name="stock_quantity_color[${newVariantIndex}]" placeholder="Stock Warna" />
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger delete-variant">
                                <i class="ki-outline ki-cross fs-1"></i>
                            </button>
                        </div>
                    </div>`;

                const $newVariant = $(newVariant).appendTo("#variant_data");
                $newVariant.show().animate({ opacity: 1 }, 500);

                // Reinitialize Select2
                $newVariant.find("[data-control='select2']").select2({
                    placeholder: "Pilih Tipe Varian",
                    allowClear: true,
                });
            });

            // Remove variant row
            $(document).on("click", ".delete-variant", function () {
                const $variantRow = $(this).closest(".variant-row");
                if (confirm("Apakah Anda yakin ingin menghapus varian ini?")) {
                    $variantRow.animate({ opacity: 0 }, 500, function () {
                        $variantRow.slideUp(300, function () {
                            $(this).remove();
                        });
                    });
                }
            });
        });

        function loadDetailData() {
            Swal.fire({
                title: 'Loading Data',
                text: 'Silahkan tunggu, sedang memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ url("/product/detail/json") }}' + '/' + '{{ $slug }}',
                method: 'GET',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    let data = result.response;

                    if ($('#status option[value="' + data.status+ '"]').length > 0) {
                        $('#status').val(data.status).trigger('change');
                    }

                    if ($('#category_id option[value="' + data.category_id + '"]').length > 0) {
                        $('#category_id').val(data.category_id).trigger('change');
                    }

                    if ($('#brand_id option[value="' + data.brand_id + '"]').length > 0) {
                        $('#brand_id').val(data.brand_id).trigger('change');
                    }

                    $('#product_name').val(data.product_name);
                    $('#description').val(data.description);
                    $('#base_price').val(data.base_price);
                    $('#total_stock').val(data.total_stock);

                    if (data.images && data.images.length > 0) {
                        data.images.forEach(function (image) {
                            image.image_url = "{{ asset('storage') }}/" + image.image_url;
                        });

                        data.images.forEach(function (image) {
                            let mockFile = {
                                name: image.image_url.split('/').pop(),
                                size: 12345,
                                dataURL: image.image_url,
                                image_id: image.id                      // Menambahkan ID gambar lama
                            };

                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("thumbnail", mockFile, image.image_url);
                            myDropzone.emit("complete", mockFile);

                            myDropzone.files.push(mockFile);
                        });
                    }

                    $('#variant_data').empty();
                    if (data.variants && data.variants.length > 0) {
                        data.variants.forEach(function (variant, index) {
                            const variantRow = `
                                <div class="variant-row mt-2">
                                    <div class="form-group d-flex flex-wrap align-items-center gap-5">
                                        <div class="w-100 w-md-200px">
                                            <select class="form-select type-variant" name="type_variant[${index}]" data-control="select2" data-hide-search="true" data-placeholder="Pilih Tipe Varian">
                                                <option></option>
                                                <option value="warna" ${variant.type_variant === 'warna' ? 'selected' : ''}>Warna</option>
                                                <option value="ukuran" ${variant.type_variant === 'ukuran' ? 'selected' : ''}>Ukuran</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control mw-100 w-200px size-variant ${variant.type_variant === 'ukuran' ? '' : 'd-none'}" name="size_variant[${index}]" value="${variant.size || ''}" placeholder="Ukuran" />
                                        <input type="text" class="form-control mw-100 w-200px color-variant ${variant.type_variant === 'warna' ? '' : 'd-none'}" name="color_variant[${index}]" value="${variant.color || ''}" placeholder="Warna" />
                                        <input type="number" class="form-control mw-100 w-200px ${variant.type_variant === 'ukuran' ? 'stock-quantity-size' : 'stock-quantity-color'}" name="${variant.type_variant === 'ukuran' ? 'stock_quantity_size' : 'stock_quantity_color'}[${index}]" value="${variant.stock_quantity || ''}" placeholder="Stock ${variant.type_variant}" />
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger delete-variant">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                </div>`;

                            $('#variant_data').append(variantRow);
                        });


                        $("[data-control='select2']").select2({
                            placeholder: "Pilih Tipe Varian",
                            allowClear: true,
                        });
                    }

                    Swal.close();
                },
                error: function(result) {
                    alertResultError(result, "Error, Data Produk gagal didapatkan.");
                }
            });
        }

        function submitForm() {
            let formData = new FormData($('#form-data')[0]);

            // Append the existing images (if any) and new images (if any) to FormData
            myDropzone.files.forEach((file, i) => {
                if (file.image_id) {
                    formData.append(`existing_images[${i}]`, file.image_id);
                } else {
                    formData.append(`product_images[${i}]`, file);
                }
            });

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
                        url: "{{ url('product/update') }}" + '/' + '{{ $slug }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil memperbarui data.', '', '/product');
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
                    resErrorSwal('Dibatalkan', 'Periksa kembali data yang anda isi.', 'warning')
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
