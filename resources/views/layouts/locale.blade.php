{!! Form::open(['route' => 'sys.dashboard.locale', 'name' => 'language_selection', 'method' => 'post']) !!}

{!! Form::hidden('action_name', getActionName()) !!}

{!! Form::select('locale', getLocaleList(), app()->getLocale(), [
    'class' => 'form-control custom-select',
    'disabled' => disableLocaleToggle(),
    'onchange' => 'this.form.submit()',
]) !!}

{!! Form::close() !!}
