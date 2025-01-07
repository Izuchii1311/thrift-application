@extends('layouts.auth_layout')

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        {{-- Body --}}
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            {{-- Form --}}
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="#" action="{{ route('login') }}" method="POST">
                        @csrf
                        {{-- Heading --}}
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Login</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Silahkan lakukan login ke dalam aplikasi dengan akun yang sudah terdaftar.</div>
                        </div>
                        
                        @php
                            /*
                                {{-- Login With Social Media --}}
                                <div class="row g-3 mb-9">
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                        <img alt="Logo" src="{{ asset('assets/dashboard/media/svg/brand-logos/google-icon.svg') }}" class="h-15px me-3" />Sign in with Google</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                        <img alt="Logo" src="{{ asset('assets/dashboard/media/svg/brand-logos/apple-black.svg') }}" class="theme-light-show h-15px me-3" />
                                        <img alt="Logo" src="{{ asset('assets/dashboard/media/svg/brand-logos/apple-black-dark.svg') }}" class="theme-dark-show h-15px me-3" />Sign in with Apple</a>
                                    </div>
                                </div>
                            */
                        @endphp

                        {{-- Separator --}}
                        <div class="separator separator-content my-14">
                            <span class="w-200px text-gray-500 fw-semibold fs-7">Selamat Datang. 👋</span>
                        </div>
                        
                        {{-- Alert --}}
                        @if ($errors->has('error'))
                            <div class="alert alert-danger d-flex align-items-center p-5">
                                <i class="fa-solid fa-circle-exclamation fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Ups... maaf</h4>
                                    <span>{{ $errors->first('error') }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        @endif
                        
                        {{-- Input --}}
                        <div class="fv-row mb-8">
                            <input type="text" placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="off" class="form-control bg-transparent" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- Input --}}
                        <div class="fv-row mb-3">
                            <div class="input-group">
                                <input type="password" placeholder="Password" id="password" name="password" value="{{ old('password') }}" autocomplete="off" class="form-control bg-transparent" />
                                <button type="button" onclick="showPassword()" class="btn btn-light-primary btn-icon input-group-text">
                                    <i id="icon-password" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>                        
                        
                        {{-- Forgot Password --}}
                        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                            <div></div>
                            <a href="#" class="link-primary">Lupa kata sandi ?</a>
                        </div>
                        
                        {{-- Submit password --}}
                        <div class="d-grid mb-10">
                            <button type="submit" id="btn-submit" class="btn btn-primary">
                                {{-- Login --}}
                                <span class="indicator-label">Login</span>
                                
                                {{-- <span class="indicator-progress">Silahkan tunggu... --}}
                                {{-- <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span> --}}
                            </button>
                        </div>
                        
                        <div class="text-gray-500 text-center fw-semibold fs-6">Belum punya akun?
                        <a href="#" class="link-primary">Daftar...</a></div>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <div class="w-lg-500px d-flex flex-stack px-10 mx-auto justify-content-center">

                {{-- <div class="me-10">
                    <!--begin::Toggle-->
                    <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                        <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="{{ asset('assets/dashboard/media/flags/united-states.svg') }}" alt="" />
                        <span data-kt-element="current-lang-name" class="me-1">English</span>
                        <span class="d-flex flex-center rotate-180">
                            <i class="ki-outline ki-down fs-5 text-muted m-0"></i>
                        </span>
                    </button>
                    <!--end::Toggle-->
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
                                <span class="symbol symbol-20px me-4">
                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('assets/dashboard/media/flags/united-states.svg') }}" alt="" />
                                </span>
                                <span data-kt-element="lang-name">English</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="Spanish">
                                <span class="symbol symbol-20px me-4">
                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('assets/dashboard/media/flags/spain.svg') }}" alt="" />
                                </span>
                                <span data-kt-element="lang-name">Spanish</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="German">
                                <span class="symbol symbol-20px me-4">
                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('assets/dashboard/media/flags/germany.svg') }}" alt="" />
                                </span>
                                <span data-kt-element="lang-name">German</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="Japanese">
                                <span class="symbol symbol-20px me-4">
                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('assets/dashboard/media/flags/japan.svg') }}" alt="" />
                                </span>
                                <span data-kt-element="lang-name">Japanese</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="French">
                                <span class="symbol symbol-20px me-4">
                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('assets/dashboard/media/flags/france.svg') }}" alt="" />
                                </span>
                                <span data-kt-element="lang-name">French</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                </div> --}}

                {{-- Links --}}
                <div class="d-flex fw-semibold text-primary fs-base gap-5">
                    <a href="#" target="_blank">Kebijakan & Privacy</a>
                    {{-- <a href="#" target="_blank">Plans</a> --}}
                    <a href="#" target="_blank">Hubungi Kami</a>
                </div>

            </div>
        </div>

        {{-- Side Content --}}
        <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url('{{ asset("assets/dashboard/media/misc/auth-bg.png") }}')">
            <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                {{-- Logo --}}
                <a href="#" class="mb-0 mb-lg-12">
                    <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/custom-1.png') }}" class="h-60px h-lg-75px" />
                </a>

                {{-- Image --}}
                <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{ asset('assets/dashboard/media/misc/auth-screens.png') }}" alt="" />

                <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and Productive</h1>

                {{-- Text --}}
                <div class="d-none d-lg-block text-white fs-base text-center">In this kind of post,
                <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a person they’ve interviewed
                <br />and provides some background information about
                <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their
                <br />work following this is a transcript of the interview.</div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom Javascript(used for this page only - Management User) --}}
    <script src="{{ asset('assets/dashboard/js/custom/authentication/sign-in/general.js') }}"></script>

    <script>
        function showPassword() {
            if (password == 'hide') {
                password = 'show';
                $('#password').attr('type', 'text');
                $('#icon-password').removeClass('fa-eye');
                $('#icon-password').addClass('fa-eye-slash');
            } else {
                password = 'hide';
                $('#password').attr('type', 'password');
                $('#icon-password').removeClass('fa-eye-slash');
                $('#icon-password').addClass('fa-eye');
            }
        }
    </script>
@endpush
