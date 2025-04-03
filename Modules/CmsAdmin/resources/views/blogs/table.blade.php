<div class="card-body px-4">
    {!! $dataTable->table(generateDataTableConfig()) !!}
</div>

@push('third_party_scripts')
    @include('common::__partial.buttons_datatables')
    {!! $dataTable->scripts() !!}
@endpush
