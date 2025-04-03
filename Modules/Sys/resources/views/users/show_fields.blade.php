<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('sys::models/users.fields.name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $user->name }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12">
    {!! Form::label('email', __('sys::models/users.fields.email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $user->email }}</p>
</div>

<!-- Role Field -->
<div class="col-sm-12">
    {!! Form::label('role', __('sys::models/users.fields.role')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $user->role_name }}</p>
</div>

<!-- Profile Image Field -->
<div class="col-sm-12">
    {!! Form::label('profile_image', __('sys::models/users.fields.profile_image')) !!}
    <span class="semicolon mr-3">:</span>
    <div class="d-flex align-items-end">
        @if (!empty($user->profile_image))
            <div class="mr-1">
                {!! renderFancyboxImage(USER_FILE_DIR_NAME, $user->profile_image, IMAGE_WIDTH_200, IMAGE_WIDTH_200) !!}
            </div>
            @if ($mode !== 'trash-restore')
                {!! renderImageDeleteIcon(route('sys.users.removeImage', [$user->id]), 'profile_image') !!}
            @endif
        @endif
    </div>
</div>

<!-- Email Verified At Field -->
<div class="col-sm-12">
    {!! Form::label('email_verified_at', __('sys::models/users.fields.email_verified_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $user->email_verified_at }}</p>
</div>

<!-- Active Field -->
<div class="col-sm-12">
    {!! Form::label('active', __('sys::models/users.fields.active')) !!}
    <span class="semicolon mr-3">:</span>
    @if ($mode == 'trash-restore')
        <p>{{ getActiveText($user->active) }}</p>
    @else
        <p>
            {!! $user->id != 1 && auth()->user()->id != $user->id
                ? manageRenderBsSwitchGrid('users.toggleActive', 'active', $user->id, $user->active, 'sys.users.toggleActive')
                : getActiveText($user->active) !!}
        </p>
    @endif
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('common::crud.fields.created_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $user->created_at }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', __('common::crud.fields.created_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {{ $user->created_by ? getUserDataById($user->created_by) : '' }}
    </p>
</div>

@if (!empty($user->updated_at))
    <!-- Updated At Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_at', __('common::crud.fields.updated_at')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ $user->updated_at }}</p>
    </div>
@endif

@if (!empty($user->updated_by))
    <!-- Updated By Field -->
    <div class="col-sm-12">
        {!! Form::label('updated_by', __('common::crud.fields.updated_by')) !!}
        <span class="semicolon mr-3">:</span>
        <p>{{ getUserDataById($user->updated_by) }}</p>
    </div>
@endif
