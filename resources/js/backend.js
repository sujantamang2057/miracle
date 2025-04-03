require('./bootstrap');

global.moment = require('moment');
import 'moment-timezone';

/* adminlte */
require('admin-lte/dist/js/adminlte.min.js');
require('admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js');

/* datatables */
require('datatables.net-bs4/js/dataTables.bootstrap4.min.js');
require('datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js');
require('datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js');
require('datatables.net-buttons/js/buttons.colVis.js'); // Column visibility
require('datatables.net-buttons/js/buttons.html5.js'); // HTML 5 file export
require('datatables.net-buttons/js/buttons.print.js'); // Print view button

// datatables rowreorder
require('datatables.net-rowreorder-bs4');

/* sweetalert2 */
window.swal = require('sweetalert2/dist/sweetalert2.all');

/* admin-lte plugins */
require('daterangepicker');

/* select2 */
require('admin-lte/plugins/select2/js/select2.full.min.js');

/* bootstrap-switch */
require('admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js');

/* Extra packages */
/* fancybox */
require('@fancyapps/fancybox/dist/jquery.fancybox.min.js');

// filepond & plugins
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
window.FilePond = FilePond;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;
window.FilePondPluginImageValidateSize = FilePondPluginImageValidateSize;
window.FilePondPluginImageExifOrientation = FilePondPluginImageExifOrientation;

// lang. stuffs
import ja_JA from 'filepond/locale/ja-ja.js';
window.FilePond_lang_ja_JA = ja_JA;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});
