<!-- Site Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('site_name')) }}">
    {!! Form::label('site_name', __('sys::models/site_settings.fields.site_name') . ':') !!}
    {!! Form::text('site_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('site_name')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('site_name')) }}
</div>

<!-- Site Logo Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('site_logo')) }}">
    {!! Form::label('site_logo', __('sys::models/site_settings.fields.site_logo') . ':') !!}
    <div class="clearfix"></div>
    {!! Form::hidden('site_logo_pre', null) !!}
    {!! Form::file('site_logo', [
        'id' => 'filepond1',
        'class' => 'my-pond',
        'value' => !empty($siteSetting->site_logo) ? $siteSetting->site_logo : null,
    ]) !!}
    @if (old('site_logo') && file_exists(storage_path('tmp/' . old('site_logo'))))
        {!! Form::hidden('site_logo', old('site_logo'), [
            'id' => 'filepond1-site_logo',
        ]) !!}
        <p class="m-1">{!! renderTmpImage(old('site_logo'), IMAGE_WIDTH_200) !!}</p>
    @elseif (!empty($siteSetting->site_logo))
        {!! Form::hidden('site_logo', !empty($siteSetting->site_logo) ? $siteSetting->site_logo : null, [
            'id' => 'filepond1-site_logo',
        ]) !!}
        <p class="m-1">{!! renderImage(SITE_SETTING_FILE_DIR_NAME, $siteSetting->site_logo, IMAGE_WIDTH_200) !!}</p>
    @endif
    {{ validationMessage($errors->first('site_logo')) }}
</div>

@push('page_scripts')
    <script>
        $(function() {
            var isMultiUpload = false,
                moduleName = "SiteSetting",
                upload_url = "{{ route('common.imageHandler.fileupload') }}",
                delete_url = "{{ route('common.imageHandler.destroy') }}";
            initializeFilePond("filepond1", "site_logo", moduleName, upload_url, delete_url, isMultiUpload);
        });
    </script>
@endpush

<!-- Meta Key Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('meta_key')) }}">
    {!! Form::label('meta_key', __('sys::models/site_settings.fields.meta_key') . ':') !!}
    {!! Form::text('meta_key', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('meta_key')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('meta_key')) }}
</div>

<!-- Meta Description Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('meta_description')) }}">
    {!! Form::label('meta_description', __('sys::models/site_settings.fields.meta_description') . ':') !!}
    {!! Form::text('meta_description', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('meta_description')),
        'maxlength' => 255,
    ]) !!}
    {{ validationMessage($errors->first('meta_description')) }}
</div>

<!-- Seo Robots Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('seo_robots', __('sys::models/site_settings.fields.seo_robots') . ':') !!}
    {!! Form::textarea('seo_robots', null, ['class' => 'form-control', 'maxlength' => 65535, 'maxlength' => 65535]) !!}
</div>

<!-- Admin Email Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('admin_email')) }}">
    {!! Form::label('admin_email', __('sys::models/site_settings.fields.admin_email') . ':') !!}
    {!! Form::text('admin_email', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('admin_email')),
        'maxlength' => 100,
    ]) !!}
    {{ validationMessage($errors->first('admin_email')) }}
</div>

<!-- Cc Admin Email Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('cc_admin_email')) }}">
    {!! Form::label('cc_admin_email', __('sys::models/site_settings.fields.cc_admin_email') . ':') !!}
    {!! Form::select('cc_admin_email[]', [], null, [
        'class' => 'form-control ' . validationInputClass($errors->has('cc_admin_email')),
        'maxlength' => 255,
        'id' => 'cc_admin_email',
        'multiple' => 'multiple',
    ]) !!}
    {{ validationMessage($errors->first('cc_admin_email')) }}
</div>

<!-- Cc Contact Email Field -->
<div class="form-group col-sm-6 {{ validationClass($errors->has('cc_contact_email')) }}">
    {!! Form::label('cc_contact_email', __('sys::models/site_settings.fields.cc_contact_email') . ':') !!}
    {!! Form::select('cc_contact_email[]', [], null, [
        'class' => 'form-control ' . validationInputClass($errors->has('cc_contact_email')),
        'maxlength' => 255,
        'id' => 'cc_contact_email',
        'multiple' => 'multiple',
    ]) !!}
    {{ validationMessage($errors->first('cc_contact_email')) }}
</div>

<!-- Company Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_address', __('sys::models/site_settings.fields.company_address') . ':') !!}
    {!! Form::text('company_address', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
    {{ validationMessage($errors->first('company_address')) }}
</div>

<!-- Company Tel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_tel', __('sys::models/site_settings.fields.company_tel') . ':') !!}
    {!! Form::text('company_tel', null, ['class' => 'form-control', 'maxlength' => 12, 'maxlength' => 12]) !!}
    {{ validationMessage($errors->first('company_tel')) }}
</div>

<!-- Company Tel1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_tel1', __('sys::models/site_settings.fields.company_tel1') . ':') !!}
    {!! Form::text('company_tel1', null, ['class' => 'form-control', 'maxlength' => 12, 'maxlength' => 12]) !!}
    {{ validationMessage($errors->first('company_tel1')) }}
</div>

<!-- Company Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_email', __('sys::models/site_settings.fields.company_email') . ':') !!}
    {!! Form::text('company_email', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
    {{ validationMessage($errors->first('company_email')) }}
</div>

<!-- Company Website Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_website', __('sys::models/site_settings.fields.company_website') . ':') !!}
    {!! Form::text('company_website', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
    {{ validationMessage($errors->first('company_website')) }}
</div>

<!-- Google Map Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('google_map', __('sys::models/site_settings.fields.google_map') . ':') !!}
    {!! Form::textarea('google_map', null, ['class' => 'form-control', 'maxlength' => 65535, 'maxlength' => 65535]) !!}
</div>

<!-- Google Analytics Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('google_analytics', __('sys::models/site_settings.fields.google_analytics') . ':') !!}
    {!! Form::textarea('google_analytics', null, [
        'class' => 'form-control',
        'maxlength' => 65535,
        'maxlength' => 65535,
    ]) !!}
</div>

<!-- Remarks Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('remarks', __('sys::models/site_settings.fields.remarks') . ':') !!}
    {!! Form::textarea('remarks', null, ['class' => 'form-control', 'maxlength' => 65535, 'maxlength' => 65535]) !!}
</div>

@include('common::__partial.filepond-scripts')
@include('common::__partial.select2-scripts')
@push('page_scripts')
    <script>
        $(function() {
            var ccAdminMails = JSON.parse({!! json_encode($ccAdminMails) !!});
            var ccContactMails = JSON.parse({!! json_encode($ccContactMails) !!});
            initializeSelect2('#cc_admin_email', ccAdminMails, true);
            initializeSelect2('#cc_contact_email', ccContactMails, true);
        });
    </script>
@endpush
