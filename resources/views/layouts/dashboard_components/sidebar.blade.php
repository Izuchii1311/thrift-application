<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo flex-shrink-0 d-none d-md-flex align-items-center px-8" id="kt_app_sidebar_logo">
        {{-- Logo --}}
        <a href="#">
            <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/demo42.svg') }}" class="h-25px d-none d-sm-inline app-sidebar-logo-default theme-light-show"/>
            <img alt="Logo" src="{{ asset('assets/dashboard/media/logos/demo42-dark.svg') }}" class="h-25px h-lg-25px theme-dark-show"/>
        </a>
        {{-- Aside Toggle --}}
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-1"></i>
            </div>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px">
            {{-- Menu --}}
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold px-1" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                @forelse ($menus as $menu)
                    {{-- Check Status Menu --}}
                    @php
                        $isActiveMenu = isActiveMenu($menu);
                        $hasChildren = !empty($menu['children']) && count($menu['children']) > 0;
                        $hasPath = !empty($menu['path']);
                    @endphp

                    @if ($hasChildren)
                        {{-- Divider before parent with children --}}
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-7">{{ $menu['menu_name'] }}</span>
                            </div>
                        </div>

                        {{-- Parent menu with children --}}
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isActiveMenu ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-outline {{ $menu['menu_icon'] }} fs-2"></i>
                                </span>
                                <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            {{-- Child menus --}}
                            <div class="menu-sub menu-sub-accordion">
                                @foreach ($menu['children'] as $childMenu1)
                                    @php
                                        $isChildMenuActive = isActiveMenu($childMenu1);
                                        $hasChildPath = !empty($childMenu1['path']);
                                        $hasChildChildren = !empty($childMenu1['children']) && count($childMenu1['children']) > 0;
                                    @endphp

                                    @if ($hasChildChildren)
                                        {{-- Submenu with children --}}
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isChildMenuActive ? 'here show mb-1' : '' }}">
                                            <span class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $childMenu1['menu_name'] }}</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div class="menu-sub menu-sub-accordion">
                                                @foreach ($childMenu1['children'] as $childMenu2)
                                                    <div class="menu-item">
                                                        <a class="menu-link {{ View::hasSection($childMenu2['key']) ? 'active' : '' }}" href="{{ $childMenu2['path'] }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">{{ $childMenu2['menu_name'] }}</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif ($hasChildPath)
                                        {{-- Submenu without children but has path --}}
                                        <div class="menu-item">
                                            <a class="menu-link {{ View::hasSection($childMenu1['key']) ? 'active' : '' }}" href="{{ $childMenu1['path'] }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $childMenu1['menu_name'] }}</span>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @elseif ($hasPath)
                        {{-- Menu without children but has path --}}
                        <div class="menu-item">
                            <a class="menu-link {{ View::hasSection($menu['key']) ? 'active' : '' }}" href="{{ $menu['path'] }}">
                                <span class="menu-icon">
                                    <i class="ki-outline {{ $menu['menu_icon'] }} fs-2"></i>
                                </span>
                                <span class="menu-title">{{ $menu['menu_name'] }}</span>
                            </a>
                        </div>
                    @endif
                @empty
                    {{-- No menu items --}}
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-home fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    {{-- Menu Divider - Help --}}
                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Help</span>
                        </div>
                    </div>

                    {{-- Help Menu Item --}}
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('documentation') ? 'active' : '' }}" href="https://github.com/Izuchii1311">
                            <span class="menu-icon">
                                <i class="ki-outline ki-book-square fs-2"></i>
                            </span>
                            <span class="menu-title">Dokumentasi</span>
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.dashboard_components.footer')

</div>
