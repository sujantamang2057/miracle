<!-- Modal -->
<div class="modal fade" id="imageUpdate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('common::crud.edit_image') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common::crud.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="imgUpdateContent"></div>
        </div>
    </div>
</div>
@pushOnce('page_scripts')
    <script type="text/javascript">
        $('body').on('click', '.imgUpdate', function(e) {
            e.preventDefault();
            var url = $(this).attr('data-href');
            $('#imgUpdateContent').load(url, function(response, status, xhr) {
                if (status == "error") {
                    window.swal.fire('Error');
                } else if (status == "success") {
                    $('#imageUpdate').modal('show');
                }
            });
        });
    </script>
@endpushOnce
