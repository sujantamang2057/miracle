<!-- Album Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('album_name')) }}">
    {!! Form::label('album_name', __('cmsadmin::models/video_albums.fields.album_name') . ':') !!}
    {!! Form::text('album_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('album_name')),
        'required',
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('album_name')) }}
</div>

<!-- Album Date Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('album_date')) }}">
    {!! Form::label('album_date', __('cmsadmin::models/video_albums.fields.album_date') . ':') !!}
    {!! Form::text('album_date', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('album_date')),
        'id' => 'album_date',
    ]) !!}
    {{ validationMessage($errors->first('album_date')) }}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('slug')) }}">
    {!! Form::label('slug', __('cmsadmin::models/video_albums.fields.slug') . ':') !!}
    {!! Form::text('slug', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('slug')),
        'placeholder' => __('common::crud.messages.auto_generate_slug'),
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('slug')) }}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('description')) }}">
    {!! Form::label('description', __('cmsadmin::models/video_albums.fields.description') . ':') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('description')),
        'rows' => 6,
        'maxlength' => 500,
    ]) !!}
    {{ validationMessage($errors->first('description')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
            initializeDateRangePicker('#album_date', false);
        });
    </script>
@endpush
@include('common::__partial.daterangepicker')
