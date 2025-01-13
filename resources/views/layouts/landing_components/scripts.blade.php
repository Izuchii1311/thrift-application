<script>var hostUrl = "{{ asset('assets/dashboard/') }}";</script>

<script src="{{ asset('assets/dashboard/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/dashboard/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
<script src="{{ asset('assets/dashboard/plugins/custom/typedjs/typedjs.bundle.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/custom/landing.js') }}"></script>
{{-- <script src="{{ asset('assets/dashboard/js/custom/pages/pricing/general.js') }}"></script> --}}
@stack('landing_js')

{{-- Custom Script --}}
<script>
    $(document).ready(function() {
        $('#logout-btn').click(function() {
            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Anda akan keluar dari akun ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the logout form
                    $('#logout-form').submit();
                }
            });
        });
    });

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