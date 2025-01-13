@extends('layouts.landing_layout')

@section('content')
    {{-- Header --}}
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
                                    <a href="{{ route('profileView') }}" class="me-3 text-decoration-none">
                                        <i class="ki-outline ki-user fs-1 text-info"></i>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logout-form">
                                        @csrf
                                        <button type="button" class="btn btn-danger" id="logout-btn">Logout</button>
                                    </form>
                                @else
                                    <a href="{{ route('login_view') }}" class="btn btn-success">Login</a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        {{-- Profile Picture --}}
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="{{ Str::title(Auth::user()->name) }}" class="w-100 h-100" style="object-fit: cover; max-width: 250px; max-height: 250px;" />
                                @else
                                    <div class="symbol-label fs-3 bg-light-warning text-warning">
                                        {{ Str::upper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <h1 class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ Auth::user()->name }}</h1>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                            <i class="ki-outline ki-profile-circle fs-4 me-1"></i>{{ Auth::user()->username }}
                                        </a>
                                        
                                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                            <i class="ki-outline ki-sms fs-4"></i>{{ Auth::user()->email }}
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <p class="fw-bold text-gray-800">
                                {{ $user->name ?? 'Nama tidak tersedia' }} alamat di {{ $user->alamat_lengkap ?? '-' }}.
                            </p>
                        </div>

                    </div>

                    {{-- Nav --}}
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" id="nav-tabs">
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-tab="profile" style="cursor: pointer;">Profile & Alamat</a>
                        </li>
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-tab="settings" style="cursor: pointer;">Pesanan Saya</a>
                        </li>
                        <li class="nav-item mt-2">
                            <a class="nav-link text-active-primary ms-0 me-10 py-5" data-tab="security" style="cursor: pointer;">Informasi Akun</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Cards for Tabs --}}
            <div class="card mb-5 mb-xl-10 tab-card" id="profile" style="display: block;">
                <div class="card-body">
                    <div class="card-header cursor-pointer">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Informasi Profile</h3>
                        </div>
                        <button id="edit-profile" class="btn btn-sm btn-primary align-self-center">Edit Profile</button>
                    </div>
                    <div id="profile-content" class="card-body p-9">
                        <!-- Profile Details -->
                        <form id="profile-form" style="display: none;">
                            @csrf
                            {{-- Input data --}}
                            <div class="fv-row mb-7">
                                <label class="d-block fw-semibold fs-6 mb-5">Foto profile</label>

                                <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true" style="background-image: url('{{ asset("assets/dashboard/media/avatars/blank.png") }}')">
                                    {{-- Existing Image --}}
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset("assets/dashboard/media/avatars/blank.png") }}');"></div>

                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                        <input type="file" name="profile_picture" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="remove_photo" id="remove-photo" value="false">
                                    </label>

                                    {{-- Button --}}
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </span>

                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar" id="remove-btn">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">File yang diizinkan: png, jpg, jpeg.</div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Nama Lengkap</label>
                                <div class="col-lg-8">
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Username</label>
                                <div class="col-lg-8">
                                    <input type="text" id="username" name="username" class="form-control">
                                </div>
                            </div>

                            <div class="card-header cursor-pointer mt-4 mb-4">
                                <div class="card-title pb-4">
                                    <h3 class="fw-bold">Alamat pengiriman</h3>
                                </div>
                            </div>

                            <div class="row mb-7 mt-4">
                                <label class="col-lg-4 fw-semibold text-muted">Nomor Handphone</label>
                                <div class="col-lg-8">
                                    <input type="text" id="nomor_handphone" name="nomor_handphone"  class="form-control">
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Provinsi</label>
                                <div class="col-lg-8">
                                    <select class="form-select mb-2" data-control="select2" data-placeholder="Pilih Provinsi" name="kode_provinsi" id="kode_provinsi">
                                        <!-- select2 -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kota</label>
                                <div class="col-lg-8">
                                    <select class="form-select mb-2" data-control="select2" data-placeholder="Pilih Kota" name="kode_kota" id="kode_kota">
                                        <!-- select2 -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kecamatan</label>
                                <div class="col-lg-8">
                                    <select class="form-select mb-2" data-control="select2" data-placeholder="Pilih Kecamatan" name="kode_kecamatan" id="kode_kecamatan">
                                        <!-- select2 -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kelurahan</label>
                                <div class="col-lg-8">
                                    <select class="form-select mb-2" data-control="select2" data-placeholder="Pilih Kelurahan" name="kode_kelurahan" id="kode_kelurahan">
                                        <!-- select2 -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kode Pos</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kode_pos" name="kode_pos"  class="form-control">
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Deskripsi Alamat</label>
                                <div class="col-lg-8">
                                    <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Alamat lengkap"></textarea>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Catatan Tambahan</label>
                                <div class="col-lg-8">
                                    <textarea name="catatan" id="catatan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Catatan"></textarea>
                                </div>
                            </div>

                            <button type="button" id="save-profile" class="btn btn-success">Simpan Perubahan</button>
                            <button type="button" id="cancel-profile" class="btn btn-secondary">Batal</button>
                        </form>

                        {{-- Profil View --}}
                        <div id="profile-view" class="card-body p-2">
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Nama Lengkap</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_name">{{ $user->name ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Username</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_username">{{ $user->username ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="card-header cursor-pointer mt-4 mb-4 p-0 ms-0">
                                <div class="card-title pb-4">
                                    <h3 class="fw-bold">Alamat pengiriman</h3>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Nomor Handphone</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_nomor_handphone">{{ $user->nomor_handphone ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Provinsi</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_provinsi">{{ $user->nama_provinsi ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kota</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_kota">{{ $user->nama_kota ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kecamatan</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_kecamatan">{{ $user->nama_kecamatan ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kelurahan</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_kelurahan">{{ $user->nama_kelurahan ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Kode Pos</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_kode_pos">{{ $user->kode_pos ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Alamat Lengkap</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_alamat_lengkap">{{ $user->alamat_lengkap ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Catatan</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold text-gray-800" id="fill_Catatan">{{ $user->catatan ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mb-5 mb-xl-10 tab-card" id="settings" style="display: none;">
                <div class="card-body">
                    <div class="accordion accordion-icon-toggle" id="kt_accordion_2">
                        <div class="mb-5">
                            <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_2_item_1">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                                <h3 class="fs-4 fw-semibold mb-0 ms-4">The best way to quick start business</h3>
                            </div>

                            <div id="kt_accordion_2_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_2">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate exercitationem aperiam saepe maiores maxime quidem sed ratione, facilis minus placeat consequatur natus doloremque, explicabo a facere dolorem rem aut sequi cupiditate. Eius, dolorem adipisci? Perspiciatis doloremque nesciunt, facilis sunt ipsa saepe voluptate rem quaerat consequuntur consectetur quisquam qui excepturi? Necessitatibus fuga molestias, voluptatum numquam cupiditate facilis est provident quaerat nam. Voluptatibus, eos ipsum reprehenderit quas laboriosam blanditiis perferendis itaque unde corporis quo maiores consequuntur eligendi ab laborum exercitationem eaque. Hic porro quia ut distinctio amet rem. Exercitationem, possimus nisi. Accusamus voluptas ad, aperiam quibusdam magni asperiores cupiditate voluptatum repellendus libero error est. Perferendis repudiandae amet quidem quae labore, atque sit explicabo quia soluta cumque odio error facere magni nulla dolore, minus cupiditate nihil. Suscipit soluta in commodi assumenda rerum, autem quam velit optio laborum itaque at architecto officiis laboriosam. Totam ea excepturi doloremque sint reiciendis officiis earum impedit atque facilis asperiores at rerum, optio enim animi et? Quibusdam error sed soluta consequatur praesentium omnis perspiciatis harum, nulla dolorum suscipit nemo cupiditate cum a corporis quis velit in quas ducimus temporibus ullam! Commodi iusto nesciunt dolor sint eligendi veritatis aperiam iste nihil officiis illo nemo nobis beatae porro ea, itaque odio.
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_2_item_2">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                                <h3 class="fs-4 fw-semibold mb-0 ms-4">How To Create Channel ?</h3>
                            </div>

                            <div id="kt_accordion_2_item_2" class="collapse fs-6 ps-10" data-bs-parent="#kt_accordion_2">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad repellendus, architecto aut sapiente natus facilis corporis ullam, eius soluta, est adipisci rerum dolores. Architecto distinctio ipsa, tenetur culpa consequatur nesciunt minima labore qui, repellendus deserunt iusto est odio quae numquam reprehenderit alias harum! Voluptatum ea qui numquam voluptas autem et!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5 mb-xl-10 tab-card" id="security" style="display: none;">
                <div class="card-body">
                    <h3>Security Content</h3>
                    <p>This is the security tab content.</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('landing_js')
    <script>
        Inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }).mask("#nomor_handphone");

        $(document).ready(function () {
            $('#nav-tabs .nav-link').on('click', function (e) {
                e.preventDefault();
                $('#nav-tabs .nav-link').removeClass('active');
                $('.tab-card').hide();

                $(this).addClass('active');
                const target = $(this).data('tab');
                $('#' + target).show();
            });

            $('#edit-profile').on('click', function () {
                $('#profile-view').hide();
                $('#profile-form').show();
                $(this).hide();
                $('#cancel-profile').show();
            });

            $('#cancel-profile').on('click', function () {
                $('#profile-form').hide();
                $('#profile-view').show();
                $('#edit-profile').show();
            });

            $('#save-profile').on('click', function () {
                submitForm();

                $('#profile-form').hide();
                $('#profile-view').show();

                $('#edit-profile').show();
                $('#cancel-profile').hide();
            });

            const removeBtn = $('#remove-btn');
            removeBtn.on('click', function () {
                $('#profile_picture').val('');
                $('#remove-photo').val(true);
                imageInputWrapper.css('background-image', `url('{{ asset('assets/dashboard/media/avatars/blank.png') }}')`);

                $(this).hide();
            });

            $('#kode_provinsi').select2({ placeholder: 'Pilih Provinsi', allowClear: true, width: '100%' })
                .off('change').on('change', function () {
                    $(this).select2('close');
                    const selectedProvinsi = $(this).val();
                    if (selectedProvinsi) {
                        loadKotaOptions(selectedProvinsi);
                    } else {
                        resetDropdown('#kode_kota', '#kode_kecamatan', '#kode_kelurahan');
                    }
                });

            $('#kode_kota').select2({ placeholder: 'Pilih Kota', allowClear: true, width: '100%', disabled: true })
                .off('change').on('change', function () {
                    $(this).select2('close');
                    const selectedKota = $(this).val();
                    if (selectedKota) {
                        loadKecamatanOptions(selectedKota);
                    } else {
                        resetDropdown('#kode_kecamatan', '#kode_kelurahan');
                    }
                });

            $('#kode_kecamatan').select2({ placeholder: 'Pilih Kecamatan', allowClear: true, width: '100%', disabled: true })
                .off('change').on('change', function () {
                    $(this).select2('close');
                    const selectedKecamatan = $(this).val();
                    if (selectedKecamatan) {
                        loadKelurahanOptions(selectedKecamatan);
                    } else {
                        resetDropdown('#kode_kelurahan');
                    }
                });

            $('#kode_kelurahan').select2({ placeholder: 'Pilih Kelurahan', allowClear: true, width: '100%', disabled: true });

            detailData();
        });

        // Get Detail Data
        async function detailData() {
            try {
                const result = await $.ajax({
                    url: "{{ url('profile/detail/json') }}" + "/" + "{{ $encryptedId }}",
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}" }
                });

                const data = result.response;

                // Update image or set to default
                let imageInputWrapper = $('.image-input-wrapper');
                const defaultProfilePicture = "{{ asset('assets/dashboard/media/avatars/blank.png') }}";
                const profilePictureUrl = data.profile_picture
                    ? `{{ asset('storage/${data.profile_picture}') }}`
                    : defaultProfilePicture;

                imageInputWrapper.css('background-image', `url("${profilePictureUrl}")`);
                $('#remove-photo').val(data.profile_picture ? 'false' : 'true');
                $("input[type='file']").val(null);
                $('#remove-btn').toggle(!!data.profile_picture);

                // Fill user info
                $('#name').val(data.name);
                $('#username').val(data.username);

                // Fill address details
                $('#nomor_handphone').val(data.nomor_handphone || '');
                $('#kode_pos').val(data.kode_pos || '');
                $('#alamat_lengkap').val(data.alamat_lengkap || '');
                $('#catatan').val(data.catatan || '');

                // Load dropdown data sequentially
                await loadProvinsiOptions(data.kode_provinsi);
                if (data.kode_kota) {
                    await loadKotaOptions(data.kode_provinsi, data.kode_kota);
                }
                if (data.kode_kecamatan) {
                    await loadKecamatanOptions(data.kode_kota, data.kode_kecamatan);
                }
                if (data.kode_kelurahan) {
                    await loadKelurahanOptions(data.kode_kecamatan, data.kode_kelurahan);
                }

                Swal.close();
            } catch (error) {
                // alertResultError(error, "Error, Data User gagal didapatkan.");
                console.error("Error, data user gagal didapatkan.");
            }
        }

        function resetDropdown(...selectors) {
            selectors.forEach(selector => {
                $(selector).empty().append('<option></option>').prop('disabled', true);
            });
        }

        function submitForm() {
            let formData = new FormData($('#profile-form')[0]);
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
                        url: '/profile-address/' + "{{ $encryptedId }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            const metadata = res?.metadata;
                            const errorCode = metadata?.status_code || res.status;

                            if (errorCode === 200) {
                                resSuccessSwal(res.metadata.message ?? 'Berhasil menambahkan data.', '', '/profile');
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

        // Load Data
        function loadProvinsiOptions(selected = null) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ url('/options/provinsi') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (result) {
                        const options = result.response.map(item =>
                            `<option value="${item.kode_provinsi}">${item.nama_provinsi}</option>`
                        );
                        $('#kode_provinsi').empty().append('<option></option>' + options.join(''));
                        if (selected) {
                            $('#kode_provinsi').val(selected).trigger('change');
                        }
                        resolve();
                    },
                    error: reject
                });
            });
        }

        function loadKotaOptions(provinsi, selected = null) {
            return new Promise((resolve, reject) => {
                if (!provinsi) return resolve(); // Skip if no provinsi
                $.ajax({
                    url: "{{ url('/options/kota') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", kode_provinsi: provinsi },
                    success: function (result) {
                        const options = result.response.map(item =>
                            `<option value="${item.kode_kota}">${item.nama_kota}</option>`
                        );
                        $('#kode_kota').empty().append('<option></option>' + options.join('')).prop('disabled', false);
                        if (selected) {
                            $('#kode_kota').val(selected).trigger('change');
                        }
                        resolve();
                    },
                    error: reject
                });
            });
        }

        function loadKecamatanOptions(kota, selected = null) {
            return new Promise((resolve, reject) => {
                if (!kota) return resolve(); // Skip if no kota
                $.ajax({
                    url: "{{ url('/options/kecamatan') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", kode_kota: kota },
                    success: function (result) {
                        const options = result.response.map(item =>
                            `<option value="${item.kode_kecamatan}">${item.nama_kecamatan}</option>`
                        );
                        $('#kode_kecamatan').empty().append('<option></option>' + options.join('')).prop('disabled', false);
                        if (selected) {
                            $('#kode_kecamatan').val(selected).trigger('change');
                        }
                        resolve();
                    },
                    error: reject
                });
            });
        }

        function loadKelurahanOptions(kecamatan, selected = null) {
            return new Promise((resolve, reject) => {
                if (!kecamatan) return resolve(); // Skip if no kecamatan
                $.ajax({
                    url: "{{ url('/options/kelurahan') }}",
                    method: 'POST',
                    data: { _token: "{{ csrf_token() }}", kode_kecamatan: kecamatan },
                    success: function (result) {
                        const options = result.response.map(item =>
                            `<option value="${item.kode_kelurahan}">${item.nama_kelurahan}</option>`
                        );
                        $('#kode_kelurahan').empty().append('<option></option>' + options.join('')).prop('disabled', false);
                        if (selected) {
                            $('#kode_kelurahan').val(selected).trigger('change');
                        }
                        resolve();
                    },
                    error: reject
                });
            });
        }
    </script>
@endpush
