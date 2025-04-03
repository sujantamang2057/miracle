@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('mail_tester') }}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ __('tools::common.mail_tester') }}
                </div>
            </div>
        </div>
    </section>
    <div class="content px-3">
        <div class="card mb-6">
            {!! Form::open(['route' => 'tools.mailTester.sendMail', 'id' => 'mail_tester_form']) !!}
            <div class="card-body">
                <div class="row">
                    @include('tools::mail_tester.fields')
                </div>
            </div>
            <div class="card-footer">
                @if (checkToolsPermission('mailTester.sendMail'))
                    {!! renderButton(__('tools::models/mail_testers.send_mail'), 'button', 'success', 'lime', '', 'sendMail(event)') !!}
                @endif
                @include('tools::__partial.reset')
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@include('common::__partial.select2-scripts')
@include('common::__partial.swal-scripts')
@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            var ccMails = JSON.parse({!! json_encode($ccMails) !!});
            var bccMails = JSON.parse({!! json_encode($ccMails) !!});
            initializeSelect2('#cc_email', ccMails, true);
            initializeSelect2('#bcc_email', bccMails, true);
        });

        function sendMail(event) {
            event.preventDefault();
            var url = $(this).attr('action');

            // Clear previous errors
            $('.form-group').removeClass('has-error');
            $('.is-invalid').removeClass('is-invalid');
            $('p.text-danger').text('');

            showSwalLoading();

            $.ajax({
                data: $('#mail_tester_form').serialize(),
                type: 'POST',
                cache: false,
                url: url,
                success: function(data) {
                    if (data.success == 1) {
                        window.swal.fire(data.message).then(function() {
                            location.reload();
                            hideSwalLoading();
                        });
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            $.each(data.errors, function(field, messages) {
                                var input = $('#' + field);
                                input.closest('.form-group').addClass('has-error');
                                input.addClass('is-invalid');
                                input.siblings('p.text-danger').text(messages[0]);
                            });
                            hideSwalLoading();
                        }
                    }
                }
            });
        }
    </script>
@endpush
