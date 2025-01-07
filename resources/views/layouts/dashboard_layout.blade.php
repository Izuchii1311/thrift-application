<!DOCTYPE html>

<html lang="en">
	<head>
        <base href=""/>
        {{-- Meta SEO --}}
		<title>@yield('title', env("APP_NAME"))</title>
		<meta charset="utf-8"/>
		<meta name="description" content="#"/>
		<meta name="keywords" content="#"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta property="og:locale" content="en_US"/>
		<meta property="og:type" content="article"/>
		<meta property="og:title" content="#"/>
		<meta property="og:url" content="#"/>
		<meta property="og:site_name" content="#"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Style --}}
        @include('layouts.dashboard_components.styles')

	</head>

	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
		<script>let defaultThemeMode = "light"; let themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

		{{-- App --}}
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">

                {{-- Header --}}
                @include('layouts.dashboard_components.header')

				{{-- Wrapper --}}
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                    {{-- Sidebar --}}
                    @include('layouts.dashboard_components.sidebar')

                    {{-- Content --}}
                    @yield('content')

				</div>
			</div>
		</div>

		{{-- Scroll to Top --}}
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>

        {{-- Modals --}}
        @include('layouts.dashboard_components.modals')

        {{-- Script --}}
        @include('layouts.dashboard_components.scripts')
	</body>
</html>
