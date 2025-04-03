<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    // tinymce settings
    // menubar
    var TinyMce_menubar = "file edit insert view format table tools help";
    var TinyMce_removed_menuitems = "paste, pastetext";
    // toolbar
    var TinyMce_toolbar1 = "fontfamily fontsize blocks styles";
    var TinyMce_toolbar2 =
        "undo redo | selectall | bold italic underline | alignleft aligncenter alignright alignjustify | indent outdent | bullist numlist | table | link image | code";
    // Custom Toolbar
    var CustomToolbar =
        ' | customAccordion | customButton | customDecoration | customHeading | customList | customTable | customTemplate | customUnderConstruction';
    // plugins
    var TinyMce_plugins =
        "advlist anchor autolink charmap code fullscreen image insertdatetime link lists media preview searchreplace table visualblocks";
    // additional settings
    var TinyMce_height = 400;
    var TinyMce_relative_urls = false;
    var TinyMce_language = "{{ app()->getLocale() }}";

    // font settings
    var TinyMce_font_family_formats =
        "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
    var TinyMce_font_size_formats = "8pt 10pt 12pt 14pt 18pt 24pt 36pt";

    // todo implementations later pb@ww
    // content_security_policy: "default-src 'self'"
    // referrer_policy: 'strict-origin-when-cross-origin'

    // elFinder settings
    var Elfinder_title = "File Manager";
    var Elfinder_url = "{{ route('elfinder.tinymce5') }}";
    var TinyMce_Elfinder_image_title = false;
    var TinyMce_Elfinder_file_picker_types = "image";
    // Image Upload Handler settings
    var Tinymce_images_upload_url = "{{ route('tools.filemanager.uploader') }}";

    // functions
    function elFinderBrowser(callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: Elfinder_title,
            url: Elfinder_url,
            onMessage: function(dialogApi, details) {
                if (details.mceAction === "fileSelected") {
                    const file = details.data.file;
                    // Make file info
                    const info = file.name;
                    // Provide file and text for the link dialog
                    if (meta.filetype === "file") {
                        callback(file.url, {
                            text: info,
                            title: info
                        });
                    }
                    // Provide image and alt text for the image dialog
                    if (meta.filetype === "image") {
                        callback(file.url, {
                            alt: info
                        });
                    }
                    // Provide alternative source and posted for the media dialog
                    if (meta.filetype === "media") {
                        callback(file.url);
                    }
                    dialogApi.close();
                }
            }
        });
    }

    const imagesUploadHandler = (blobInfo, progress) => new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', Tinymce_images_upload_url);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content'));
        xhr.upload.onprogress = (e) => {
            progress(e.loaded / e.total * 100);
        };
        xhr.onload = () => {
            if (xhr.status === 403) {
                reject({
                    message: 'HTTP Error: ' + xhr.status,
                    remove: true
                });
                return;
            }
            if (xhr.status < 200 || xhr.status >= 300) {
                reject('HTTP Error: ' + xhr.status);
                return;
            }
            const json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                reject('Invalid JSON: ' + xhr.responseText);
                return;
            }
            resolve(json.location);
        };
        xhr.onerror = () => {
            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    });

    function initTinyMce(selector) {
        selector = selector || 'textarea#tinymce_editor';
        tinymce.init({
            selector: selector,
            license_key: 'gpl',
            // maintain alphabetical order from here pb@ww
            font_family_formats: TinyMce_font_family_formats,
            font_size_formats: TinyMce_font_size_formats,
            content_css: "{{ asset('js/tinymce/css/custom-tinymce.css') }}",
            height: TinyMce_height,
            language: TinyMce_language,
            menubar: TinyMce_menubar,
            plugins: TinyMce_plugins,
            relative_urls: TinyMce_relative_urls,
            removed_menuitems: TinyMce_removed_menuitems,
            toolbar1: TinyMce_toolbar1,
            toolbar2: TinyMce_toolbar2,
            toolbar3: CustomToolbar,
            // elFinder
            image_title: TinyMce_Elfinder_image_title,
            file_picker_types: TinyMce_Elfinder_file_picker_types,
            file_picker_callback: elFinderBrowser,
            // Image Upload Handler
            images_upload_handler: imagesUploadHandler,

            // customization
            setup: function(editor) {
                editor.on('init', function() {
                    editor.on('click', function(ed, e) {
                        var currentTarget = ed.target;
                        var className = currentTarget.className;
                        if (className == "ac_button") {
                            var el = currentTarget.nextSibling;
                            var displayVal = getComputedStyle(el, null).display;
                            if (displayVal == "block") {
                                el.style.display = "none";
                            } else {
                                el.style.display = "block";
                            }
                        }
                    });
                });

                addButtons.forEach(function(item, index, arr) {
                    var name = item.name;
                    var registry = editor.ui.registry;
                    var myFunc = eval(name + '(editor)');
                    if (item.type == 'menu') {
                        registry.addMenuButton(name, myFunc);
                    } else if (item.type == 'split') {
                        registry.addSplitButton(name, myFunc);
                    }
                });
            }
        });
    }

    function destroyTinyMce() {
        tinymce.activeEditor.destroy();
    }
    initTinyMce();
</script>
