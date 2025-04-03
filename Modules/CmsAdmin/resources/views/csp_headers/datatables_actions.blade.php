
<input type="hidden" name="id" value="{{ $csp_id }}" />
<div class='action-buttons action-col-4'>

    @if ($reserved == 2 && checkCmsAdminPermissionList(['cspHeaders.edit', 'cspHeaders.update']))
        <a href="{{ route('cmsadmin.cspHeaders.edit', $csp_id) }}" class="btn btn-sm btn-sm text-primary cspHeaderEdit" title="{{ __('common::crud.edit') }}">
            <i class="fa fa-pen"></i>
        </a>
    @endif
</div>
