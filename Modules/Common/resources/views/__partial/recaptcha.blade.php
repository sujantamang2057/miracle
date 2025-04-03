@if (env('ENABLE_RECAPTCHA', false))
    @pushOnce('page_scripts')
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ env('RECAPTCHAV2_SITE_KEY', '') }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    @endPushOnce
@endif
