<div id="kt_app_header" class="app-header">
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        {{-- Sidebar Toggle --}}
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            {{-- Logo Image --}}
            <a href="#">
                <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/demo42-small.svg') }}" class="h-30px"/>
            </a>
        </div>

        {{-- Toolbar Wrapper --}}
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            {{-- Notification --}}
            <div class="app-navbar-item ms-1 ms-md-3">
                {{-- Wrapper --}}
                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <i class="ki-outline ki-calendar fs-1"></i>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="app-navbar-item ms-1 ms-md-3">
                {{-- Wrapper --}}
                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <i class="ki-outline ki-abstract-26 fs-1"></i>
                </div>
            </div>

        </div>
    </div>
</div>
