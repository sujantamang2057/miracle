<!-- Site Name Field -->
<div class="col-sm-12">
    {!! Form::label('site_name', __('sys::models/site_settings.fields.site_name')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->site_name }}</p>
</div>

<!-- Site Logo Field -->
<div class="col-sm-12">
    {!! Form::label('site_logo', __('sys::models/site_settings.fields.site_logo')) !!}
    <span class="semicolon mr-3">:</span>
    <p>
        {!! renderFancyboxImage(SITE_SETTING_FILE_DIR_NAME, $siteSetting->site_logo, IMAGE_WIDTH_200) !!}
    </p>
</div>

<!-- Meta Key Field -->
<div class="col-sm-12">
    {!! Form::label('meta_key', __('sys::models/site_settings.fields.meta_key')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->meta_key }}</p>
</div>

<!-- Meta Description Field -->
<div class="col-sm-12">
    {!! Form::label('meta_description', __('sys::models/site_settings.fields.meta_description')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->meta_description }}</p>
</div>

<!-- Seo Robots Field -->
<div class="col-sm-12">
    {!! Form::label('seo_robots', __('sys::models/site_settings.fields.seo_robots')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! nl2br($siteSetting->seo_robots ?? '') !!} </pre>
</div>

<!-- Admin Email Field -->
<div class="col-sm-12">
    {!! Form::label('admin_email', __('sys::models/site_settings.fields.admin_email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->admin_email }}</p>
</div>

<!-- Cc Admin Email Field -->
<div class="col-sm-12">
    {!! Form::label('cc_admin_email', __('sys::models/site_settings.fields.cc_admin_email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->cc_admin_email }}</p>
</div>

<!-- Cc Contact Email Field -->
<div class="col-sm-12">
    {!! Form::label('cc_contact_email', __('sys::models/site_settings.fields.cc_contact_email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->cc_contact_email }}</p>
</div>

<!-- Company Address Field -->
<div class="col-sm-12">
    {!! Form::label('company_address', __('sys::models/site_settings.fields.company_address')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->company_address }}</p>
</div>

<!-- Company Tel Field -->
<div class="col-sm-12">
    {!! Form::label('company_tel', __('sys::models/site_settings.fields.company_tel')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->company_tel }}</p>
</div>

<!-- Company Tel1 Field -->
<div class="col-sm-12">
    {!! Form::label('company_tel1', __('sys::models/site_settings.fields.company_tel1')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->company_tel1 }}</p>
</div>

<!-- Company Email Field -->
<div class="col-sm-12">
    {!! Form::label('company_email', __('sys::models/site_settings.fields.company_email')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->company_email }}</p>
</div>

<!-- Company Website Field -->
<div class="col-sm-12">
    {!! Form::label('company_website', __('sys::models/site_settings.fields.company_website')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->company_website }}</p>
</div>

<!-- Google Map Field -->
<div class="col-sm-12">
    {!! Form::label('google_map', __('sys::models/site_settings.fields.google_map')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{{ $siteSetting->google_map }}</pre>
</div>

<!-- Google Analytics Field -->
<div class="col-sm-12">
    {!! Form::label('google_analytics', __('sys::models/site_settings.fields.google_analytics')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{{ $siteSetting->google_analytics ?? '' }}</pre>
</div>

<!-- Remarks Field -->
<div class="col-sm-12">
    {!! Form::label('remarks', __('sys::models/site_settings.fields.remarks')) !!}
    <span class="semicolon mr-3">:</span>
    <pre>{!! nl2br($siteSetting->remarks ?? '') !!}</pre>
</div>

<!-- Updated By Field -->
<div class="col-sm-12">
    {!! Form::label('updated_by', __('sys::models/site_settings.fields.updated_by')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ getUserDataById($siteSetting->updated_by) }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('sys::models/site_settings.fields.updated_at')) !!}
    <span class="semicolon mr-3">:</span>
    <p>{{ $siteSetting->updated_at }}</p>
</div>
