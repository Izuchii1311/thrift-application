{{-- <script>var hostUrl = "assets/";</script> --}}
<script>var hostUrl = "{{ asset('assets/') }}";</script>

{{-- Global Javascript Template --}}
<script src="{{ asset('assets/dashboard/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/scripts.bundle.js') }}"></script>

{{-- Vendors--}}
<script src="{{ asset('assets/dashboard/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
<script src="{{ asset('assets/dashboard/plugins/custom/datatables/datatables.bundle.js') }}"></script>

{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

{{-- Dropzone --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script> --}}

{{-- Aos --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init();
</script>

@stack('js')

{{-- Custom --}}
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "2000",
        "hideDuration": "2000",
        "timeOut": "2000",
        "extendedTimeOut": "2000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function applyDropzoneTheme(theme, el) {
        const $dropzone = $(el);

        if (theme === 'dark') {
            $dropzone.css({
                'background-color': '#1e1e2d',
                'color': '#fff',
            });
            $dropzone.find('.text-gray-900').css('color', '#fff');
            $dropzone.find('.text-gray-400').css('color', '#aaa');
        } else {
            $dropzone.css({
                'background-color': '#fff',
                'color': '#000',
            });
            $dropzone.find('.text-gray-900').css('color', '#1e1e2d');
            $dropzone.find('.text-gray-400').css('color', '#5e5e6d');
        }
    }
</script>

{{-- Custom Script --}}
<script>
    /**
     * Show Loading Animation - Sweet Alert
     */
    function showLoading() {
        Swal.fire({
            icon: 'info',
            title: "Mohon Tunggu!",
            html: "Data sedang dalam proses",
            allowOutsideClick: false,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }

    /**
     * Show Alert Result Error
     */
     function alertResultError(result, alertMessage = 'Error loading data Options.') {
        const errorMessage = result.responseJSON?.message || `${result.statusText} (${result.status})`;
        alert(`${alertMessage}: ${errorMessage}`);
        Swal.close();
    }

    /**
     * Show Response success Sweet Alert
     */
    function resSuccessSwal(message, el = null, redirectUrl = null) {
        Swal.fire({
            title: 'Sukses',
            text: message,
            icon: 'success',
            confirmButtonText: 'Ok',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    if (el) {
                        $(el).modal('hide');
                    }
                    table.draw();
                }
            }
        });
    }

    /**
     * Show Response error Sweet Alert
     */
    const resErrorSwal = (title, message, icon) => Swal.fire({ title: title, html: message, icon: icon });

    /**
     * Handle Error data using Status Code 
     */
     function handleResError(res) {
        const metadata = res?.metadata || {};
        console.log(res)
        const errorCode = metadata?.status_code || res?.status || 0;

        let errorMessage = '';

        switch (errorCode) {
            case 404:
                return resErrorSwal('Peringatan!', 'Error 404: Resource not found.', 'error');
            
            case 429:
                errorMessage = metadata?.message ?? res?.message ?? 'Error 429: Too Many Requests.';
                return resErrorSwal('Peringatan!', errorMessage, 'error');
            
            case 500:
                errorMessage = metadata?.message ?? res?.message ?? 'Error 500: Internal Server Error.';
                return resErrorSwal('Peringatan!', errorMessage, 'error');

            case 400:
            case 422:
                const validationErrors = Object.values(metadata?.errors || {}).flat().map(msg => `<p>${msg}</p>`).join('');
                errorMessage = validationErrors || metadata?.message || 'Error 400: Bad Request.';
                
                return resErrorSwal('Peringatan!', errorMessage, 'warning');

            default:
                errorMessage = metadata?.message || res?.statusText || 'Terjadi kesalahan yang tidak terduga.';
                return resErrorSwal('Peringatan!', errorMessage, 'error');
        }
    }
</script>