@hasanyrole(HAS_ANY_ROLE_MASTER_OR_ADMIN)
    @extends('layouts.app')

    @section('content')
        {{ Breadcrumbs::render('tools') }}
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>{{ __('tools::common.tools') }}</h1>
                    </div>
                </div>
            </div>
        </section>
        @include('tools::default.tools_list')
    @endsection
@endhasanyrole
