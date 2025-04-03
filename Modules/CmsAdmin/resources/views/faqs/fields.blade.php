<!-- Faq Cat Id Field -->
<div class="form-group col-sm-12 required {{ validationClass($errors->has('faq_cat_id')) }}">
    {!! Form::label('faq_cat_id', __('cmsadmin::models/faqs.fields.faq_cat_id') . ':') !!}
    {!! Form::select('faq_cat_id', $faqCategoryList, null, [
        'class' => 'form-control ' . validationInputClass($errors->has('faq_cat_id')),
    ]) !!}
    {{ validationMessage($errors->first('faq_cat_id')) }}
</div>

<!-- Question Field -->
<div class="form-group col-sm-12 required {{ validationClass($errors->has('question')) }}">
    {!! Form::label('question', __('cmsadmin::models/faqs.fields.question') . ':') !!}
    {!! Form::text('question', null, [
        'class' => 'form-control',
        'required',
        'maxlength' => 191,
    ]) !!}
    {{ validationMessage($errors->first('question')) }}
</div>

<!-- Answer Field -->
<div class="form-group col-md-12 required {{ validationClass($errors->has('question')) }}">
    {!! Form::label('answer', __('cmsadmin::models/faqs.fields.answer') . ':') !!}
    {!! Form::textarea('answer', null, [
        'class' => 'form-control',
        'required',
        'maxlength' => 65535,
        'rows' => 10,
    ]) !!}
    {{ validationMessage($errors->first('answer')) }}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        });
    </script>
@endpush
