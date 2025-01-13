@extends('layouts.auth_layout')

@section('content')
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        {{-- Body --}}
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            {{-- Form --}}
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">
                    <form class="form w-100" novalidate="novalidate" id="formData" action="{{ route('login') }}" method="POST">
                        @csrf
                        {{-- Heading --}}
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Login</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Silahkan lakukan login ke dalam aplikasi dengan akun yang sudah terdaftar.</div>
                        </div>
                        

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
                        {{-- <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                            <div></div>
                            <a href="#" class="link-primary">Lupa kata sandi ?</a>
                        </div> --}}
                        
                        {{-- Submit password --}}
                        <div class="d-grid mb-10">
                            <button type="submit" id="btn-submit" class="btn btn-primary">
                                {{-- Login --}}
                                <span class="indicator-label">Login</span>
                            </button>
                        </div>
                        
                        <div class="text-gray-500 text-center fw-semibold fs-6">Belum punya akun?
                        <a href="{{ route('register_view') }}" class="link-primary">Daftar...</a></div>
                    </form>
                </div>
                    <a href="{{ route('landing-index') }}" class="link-primary">Kembali</a></div>
            </div>

            {{-- Footer --}}
            {{-- <div class="w-lg-500px d-flex flex-stack px-10 mx-auto justify-content-center">
                <div class="d-flex fw-semibold text-primary fs-base gap-5">
                    <a href="#" target="_blank">Kebijakan & Privacy</a>
                    <a href="#" target="_blank">Hubungi Kami</a>
                </div>
            </div> --}}
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
