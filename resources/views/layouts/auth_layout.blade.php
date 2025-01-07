<!DOCTYPE html>

<html lang="en">
	<head>
        <base href=""/>
        {{-- Meta SEO --}}
		<title>Base Code 1</title>
		<meta charset="utf-8" />
		<meta name="description" content="#" />
		<meta name="keywords" content="#" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="#" />
		<meta property="og:url" content="#" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />

		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="shortcut icon" href="{{ asset('assets/dashboard/media/logos/favicon.ico') }}" />

        {{-- Global Font --}}
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

		{{-- Global CSS Style --}}
		<link href="{{ asset('assets/dashboard/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/dashboard/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

        {{-- Font Awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

        @stack('css')

		{{-- Custom Javascript --}}
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>

	<body id="kt_body" class="app-blank">
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>

		{{-- App --}}
		<div class="d-flex flex-column flex-root" id="kt_app_root">

            @yield('content')

		</div>

		{{-- <script>var hostUrl = "assets/";</script> --}}
        <script>var hostUrl = "{{ asset('assets/') }}";</script>

        {{-- Global Javascript Template --}}
        <script src="{{ asset('assets/dashboard/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/dashboard/js/scripts.bundle.js') }}"></script>

        @stack('js')
	</body>
</html>
