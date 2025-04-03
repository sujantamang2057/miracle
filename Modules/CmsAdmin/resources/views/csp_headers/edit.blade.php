<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">
        {{ __('common::crud.edit') . ' - ' . '(' . $cspHeader->directive . ')' }}
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common::crud.close') }}">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="card">
    {!! Form::model($cspHeader, [
        'route' => ['cmsadmin.cspHeaders.update', $cspHeader->csp_id],
        'method' => 'patch',
        'id' => 'approveApplicantForm',
    ]) !!}
    <div class="card-body">
        <div class="row">
            @include('cmsadmin::csp_headers.fields')
        </div>
    </div>

    <div class="d-flex justify-content-center" style="margin-bottom: 20px;">
        <button type='submit' class='btn btn-sm btn-primary lime' id="submitApprovalBtn" style="margin-right: 10px">
            <i class='fa fa-save'></i> {{ __('common::crud.submit') }}
        </button>
        {!! renderLinkButton(__('common::crud.cancel'), route('cmsadmin.cspHeaders.index'), 'times', 'warning', 'ms-3') !!}
    </div>

    <script type="text/javascript">
        $(function() {
           
            var directives = JSON.parse({!! json_encode($cspHeader->csp_id) !!});
            var keywords = JSON.parse({!! json_encode($cspHeader->csp_id) !!});
            var schemas = JSON.parse({!! json_encode($cspHeader->csp_id) !!});
            var values = JSON.parse({!! json_encode($cspHeader->csp_id) !!});
            initializeSelect2('#directive', directives, true);
            initializeSelect2('#keyword', keywords, false);
            initializeSelect2('#schema', schemas, false);
            initializeSelect2('#value', values, true);

        });
    </script>
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
    {!! Form::close() !!}
</div>
