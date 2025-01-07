@extends('layouts.dashboard_layout')
@section('title', $menu_path ? $menu_path->menu_name : 'Dashboard')
@section('dashboard', 'here')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        {{-- Wrapper --}}
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                {{-- Content Container --}}
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <a href="#" class="card hover-elevate-up shadow-sm parent-hover">
                        <div class="card-body d-flex align-items">
                            <span class="svg-icon fs-4">
                                <i class="fas fa-rocket"></i>
                            </span>

                            <span class="ms-3 text-gray-700 parent-hover-primary fs-6 fw-bold">
                                Welcome to Dashboard
                            </span>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div id="kt_app_footer" class="app-footer">
            <div class="app-container container-xxl d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                {{-- Copyright --}}
                <div class="text-dark order-2 order-md-1">
                    <span class="text-muted fw-semibold me-1">2023&copy;</span>
                    <a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Keenthemes</a>
                </div>

                <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                    <li class="menu-item">
                        <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
                    </li>
                    <li class="menu-item">
                        <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
                    </li>
                    <li class="menu-item">
                        <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom Javascript(used for this page only - Dashboard) --}}
    <script src="{{ asset('assets/dashboard/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/custom/utilities/modals/users-search.js') }}"></script>
@endpush
