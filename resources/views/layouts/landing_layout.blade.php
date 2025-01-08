<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href=""/>
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
		
        @include('layouts.landing_components.styles')
	</head>
	<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

		<div class="d-flex flex-column flex-root" id="kt_app_root">
            
            @yield('content')

            @include('layouts.landing_components.footer ')

			<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
				<i class="ki-outline ki-arrow-up"></i>
			</div>
		</div>

        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>

        @include('layouts.landing_components.scripts')

	</body>
</html>