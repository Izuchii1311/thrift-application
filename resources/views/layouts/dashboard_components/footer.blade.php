<div class="app-sidebar-footer d-flex align-items-center px-8 pb-10" id="kt_app_sidebar_footer">
    <div class="">
        {{-- User Information --}}
        <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-overflow="true" data-kt-menu-placement="top-start">
            <div class="d-flex flex-center cursor-pointer symbol symbol-circle symbol-40px">
                <img src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}" alt="image"/>
            </div>
            {{-- Name --}}
            <div class="d-flex flex-column align-items-start justify-content-center ms-3">
                <span class="text-gray-500 fs-8 fw-semibold">Welcome</span>
                <a class="text-gray-800 fs-7 fw-bold text-hover-primary">{{ Auth::user()->name }}</a>
            </div>
        </div>

        {{-- User Account --}}
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
            {{-- Menu Item --}}
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    {{-- Avatar --}}
                    <div class="symbol symbol-50px me-5">
                        <img alt="Logo" src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}"/>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ \App\Models\User::userRoleActiveInfo()['role_active_as'] }}</span></div>
                        <a class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                    </div>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator my-2"></div>

            {{-- Menu Item --}}
            <div class="menu-item px-5">
                <a class="menu-link px-5">Profile</a>
            </div>

            {{-- Menu Item --}}
            @if (!empty($roles))
                @if (count($roles) > 1)
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-end" data-kt-menu-offset="-15px, 0">
                        <a class="menu-link px-5">
                            <span class="menu-title">Ganti Akun</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                            @foreach ($roles as $role)
                                <div class="menu-item px-3">
                                    <a class="menu-link px-5 switch-role" data-role-id="{{ $role['id'] }}">
                                        {{ $role['display_name'] }}
                                        @if ($role['id'] == $role_active_id)
                                            <span class="badge badge-success ms-2">Aktif</span>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            {{-- Separator --}}
            <div class="separator my-2"></div>

            {{-- Menu Item --}}
            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a class="menu-link px-5">
                    <span class="menu-title position-relative">Mode
                    <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                        <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                        <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                    </span></span>
                </a>
                {{-- Menu --}}
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                    {{-- Menu Item --}}
                    <div class="menu-item px-3 my-0">
                        <a class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-night-day fs-2"></i>
                            </span>
                            <span class="menu-title">Light</span>
                        </a>
                    </div>

                    <div class="menu-item px-3 my-0">
                        <a class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-moon fs-2"></i>
                            </span>
                            <span class="menu-title">Dark</span>
                        </a>
                    </div>

                    <div class="menu-item px-3 my-0">
                        <a class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-screen fs-2"></i>
                            </span>
                            <span class="menu-title">System</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Menu Item --}}
            <div class="menu-item px-5">
                <a id="logoutButton" class="menu-link px-5">Logout</a>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('.switch-role').on('click', function(e) {
                e.preventDefault();
                let roleId = $(this).data('role-id');

                Swal.fire({
                    title: 'Apakah anda yakin ingin mengganti ke role yang dipilih ?',
                    icon: 'warning',
                    showDenyButton: true,
                    denyButtonText: 'Tidak',
                    confirmButtonText: 'Ya, Ganti!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengganti Role Akun...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('dashboard.changeRole') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                role_id: roleId
                            },
                            success: function() {
                                window.location.href = "{{ route('dashboard.index') }}";
                            },
                            error: function() {
                                Swal.fire('Error', 'Gagal mengganti role. Silakan coba lagi.', 'error');
                            }
                        });
                    }
                });
            })

            $('#logoutButton').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda yakin ingin melakukan logout ?',
                    icon: 'warning',
                    showDenyButton: true,
                    denyButtonText: 'Tidak',
                    confirmButtonText: 'Ya, Logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Logging out...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('logout') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                window.location.href = "{{ route('login') }}";
                            },
                            error: function() {
                                Swal.fire('Error', 'Gagal logout. Silakan coba lagi.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
