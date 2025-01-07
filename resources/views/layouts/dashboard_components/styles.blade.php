<link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
<link rel="shortcut icon" href="{{ asset('assets/dashboard/media/logos/favicon.ico') }}"/>

{{-- Global Font --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>

{{-- Custom CSS (Used for this page only - Dashboard) --}}
<link href="{{ asset('assets/dashboard/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/dashboard/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>

{{-- Global CSS Style --}}
<link href="{{ asset('assets/dashboard/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/dashboard/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

{{-- Laravel Notify --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/styles/metro/notify-metro.min.css" />

{{-- Toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

{{-- Dropzone --}}
<link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">

{{-- Aos --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

{{-- Custom Jvascript --}}
<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>

@stack('css')

<style>
    /* Toastr */
    .toast {
        box-shadow: none !important;
    }
    .toast:hover {
        box-shadow: none !important;
        transition: box-shadow 0.3s ease;
    }
</style>