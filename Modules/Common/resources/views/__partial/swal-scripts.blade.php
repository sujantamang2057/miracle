@pushOnce('page_scripts')
    <script>
        function hideSwalLoading() {
            window.swal.close();
        }

        function showSwalLoading() {
            window.swal.showLoading();
        }

        function autoCloseSwal(msg, type, timer, timerProgressBar) {
            if (msg) {
                type = (typeof type != 'undefined') ? type : 'info';
                timer = (typeof timer != 'undefined') ? timer : 2000;
                timerProgressBar = (typeof timerProgressBar != 'undefined') ? timerProgressBar : false;

                window.swal.fire({
                    icon: type,
                    title: msg,
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: timerProgressBar,
                });
            }
        }
    </script>
@endPushOnce
