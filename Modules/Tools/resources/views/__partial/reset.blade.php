<button type="button" class="btn btn-warning" id="resetBtn"> {{ __('common::crud.reset') }}</button>

@push('page_scripts')
    <script>
        $(function() {
            $('body').on('click', '#resetBtn', function(e) {
                e.preventDefault();
                var parentForm = $(this).parents('form');
                if (parentForm.length > 0) {
                    parentForm[0].reset();
                    if ($('.select2-hidden-accessible').length > 0) {
                        $('.select2-hidden-accessible').trigger('change');
                    }
                    if ($('#image_name').length > 0) {
                        $('#image_name').find('option').not(':first').remove();
                    }
                    if ($('button:disabled').length > 0) {
                        $('button:disabled').removeAttr('disabled');
                    }
                    $('.response').html('');
                }
            });
        });
    </script>
@endpush
