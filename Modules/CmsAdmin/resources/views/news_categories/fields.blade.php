<!-- Category Name Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('category_name')) }}">
    {!! Form::label('category_name', __('cmsadmin::models/news_categories.fields.category_name') . ':') !!}
    {!! Form::text('category_name', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('category_name')),
        'maxlength' => 150,
    ]) !!}
    {{ validationMessage($errors->first('category_name')) }}
</div>

<!-- Parent Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_category_id', __('cmsadmin::models/news_categories.fields.parent_category_id') . ':') !!}
    {!! Form::select('parent_category_id', $parentCategoriesList, null, [
        'class' => 'form-control',
    ]) !!}
    {{ validationMessage($errors->first('parent_category_id')) }}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6 required {{ validationClass($errors->has('slug')) }}">
    {!! Form::label('slug', __('cmsadmin::models/news_categories.fields.slug') . ':') !!}
    {!! Form::text('slug', null, [
        'class' => 'form-control ' . validationInputClass($errors->has('slug')),
        'placeholder' => __('common::crud.messages.auto_generate_slug'),
        'maxlength' => 150,
    ]) !!}
    {{ validationMessage($errors->first('slug')) }}
</div>

<div class="form-group col-sm-12">
    <!-- Publish Field -->
    <div class="form-group {{ validationClass($errors->has('publish')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('publish', __('common::crud.fields.publish') . ':') !!}
            @if (checkCmsAdminPermission('newsCategories.togglePublish'))
                {!! Form::hidden('publish', 2) !!}
                {!! renderBootstrapSwitchPublish('publish', $id, $publish, old('publish')) !!}
                {{ validationMessage($errors->first('publish')) }}
            @else
                {{ getPublishText(2) }}
            @endif
        </div>
    </div>

    <!-- Reserved Field -->
    <div class="form-group {{ validationClass($errors->has('reserved')) }}">
        <div class="form-check mr-3 pl-0">
            {!! Form::label('reserved', __('common::crud.fields.reserved') . ':') !!}
            @if (checkCmsAdminPermission('newsCategories.toggleReserved'))
                {!! Form::hidden('reserved', 2) !!}
                {!! renderBootstrapSwitchReserved('reserved', $id, $reserved, old('reserved')) !!}
                {{ validationMessage($errors->first('reserved')) }}
            @else
                {{ getReservedText(2) }}
            @endif
        </div>
    </div>
    @if (getActionName() == 'edit')
        {!! renderSubmitButton(__('common::crud.btn.update'), 'primary', '') !!}
    @else
        {!! renderSubmitButton(__('common::crud.btn.create'), 'success', '') !!}
    @endif
    {!! renderLinkButton(__('common::crud.btn.cancel'), route('cmsadmin.newsCategories.index'), 'times', 'warning', '') !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="switch"]').bootstrapSwitch('state', $(this).prop('checked'));
        })
    </script>
@endpush
