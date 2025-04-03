<!-- Block Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('block_name')) }}">
    {!! Form::label('block_name', __('cmsadmin::models/blocks.fields.block_name') . ':') !!}
    {!! Form::text('block_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('block_name')),
        'maxlength' => 50,
    ]) !!}
    {{ validationMessage($errors->first('block_name')) }}
</div>

<!-- Filename Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('filename')) }}">
    {!! Form::label('filename', __('cmsadmin::models/blocks.fields.filename') . ':') !!}
    {!! Form::text('filename', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('filename')),
        'maxlength' => 50,
    ]) !!}
    {{ validationMessage($errors->first('filename')) }}
</div>

<!-- File Contents Field -->
<div class="form-group col-sm-12 col-lg-12 {{ validationClass($errors->has('file_contents')) }}">
    {!! Form::label('file_contents', __('cmsadmin::models/blocks.fields.file_contents') . ':') !!}
    {!! Form::textarea('file_contents', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('file_contents')),
        'maxlength' => 65535,
    ]) !!}
    {{ validationMessage($errors->first('file_contents')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
@endpush
