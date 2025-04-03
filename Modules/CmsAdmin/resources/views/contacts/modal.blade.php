<!-- Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('cmsadmin::models/contacts.resend_mail') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @isset($contact)
                @include('cmsadmin::contacts.modal_detail')
            @endisset
        </div>
    </div>
</div>

@include('common::__partial.swal-scripts')
@pushOnce('page_scripts')
    <script>
        $(function() {
            $('body').on('click', '#resend-mail', function(e) {
                e.preventDefault();
                var url = $(this).attr('data-url');
                showSwalLoading();
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: url,
                    success: function(response) {
                        $('#contactModal').modal('hide');
                        if (response.success) {
                            window.swal.fire(response.message).then(function() {
                                location.reload();
                                hideSwalLoading();
                            });
                        } else if (response.success == false && response.message) {
                            window.swal.fire(response.message);
                        }
                    }
                });
            });
            @if (getActionName() == 'index')
                $('body').on('click', '.load-resend-mail', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $('#contactModal .modal-content').html('');
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: url,
                        success: function(response) {
                            if (response && response.html) {
                                $('#contactModal .modal-content').append(response.html);
                                $('#contactModal').modal('show');
                            }
                        }
                    });
                });
            @endif
        });
    </script>
@endpushOnce
