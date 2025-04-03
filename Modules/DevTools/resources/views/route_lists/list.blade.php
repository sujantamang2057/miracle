<table class="table-bordered data-table table">
    <thead>
        <tr>
            <th class="sn-col">{{ __('devtools::route_lists.fields.sn') }}</th>
            <th class="method-col">{{ __('devtools::route_lists.fields.method') }}</th>
            <th class="url-col">{{ __('devtools::route_lists.fields.url') }}</th>
            <th class="log-name-col">{{ __('devtools::route_lists.fields.name') }}</th>
            <th class="">{{ __('devtools::route_lists.fields.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($routes as $key => $route)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $route->methods()[0] }}</td>
                <td>{{ $route->uri() }}</td>
                <td>{{ $route->getName() }}</td>
                <td>{{ $route->getActionName() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
