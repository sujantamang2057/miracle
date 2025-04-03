<script src="{{ asset('js/tinymce/customTinymce/custom-tinymce.js') }}"></script>
<script src="{{ asset('js/tinymce/customTinymce/lang/' . getAppLocaleEx() . '_custom.js') }}"></script>

<script>
    function trans(key, replace = {}) {
        var translatedString = key.split('.').reduce((t, i) => t[i] || null, window.customTimymceLang);

        for (var keyToReplace in replace) {
            translatedString = translatedString.replace(`:${keyToReplace}`, replace[keyToReplace]);
        }
        return translatedString;
    }
</script>
