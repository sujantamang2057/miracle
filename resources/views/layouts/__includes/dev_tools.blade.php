<li class="nav-item {{ setActiveMenuDevTools(['info*', 'route-lists*', 'log-viewer*']) }}">
    <a href="#" class="nav-link {{ setActiveMenuDevTools(['info*', 'route-lists*', 'log-viewer*'], true) }}">
        <i class="nav-icon fa fa-cubes"></i>
        <p>{{ __('common::backend.menu.dev_tools') }}<i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('devtools.phpInfo.index') }}" class="nav-link {{ setActiveMenuDevTools(['info*'], true) }}">
                <i class="nav-icon fa fa-info-circle"></i>
                <p>{{ __('devtools::common.php_info') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('devtools.dev.index') }}" class="nav-link {{ setActiveMenuDevTools(['route-lists*'], true) }}">
                <i class="nav-icon fa fa-route"></i>
                <p>{{ __('devtools::common.route_lists') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('log-viewer::dashboard') }}" class="nav-link {{ setActiveMenuDevTools(['log-viewer*'], true) }}" target="_blank">
                <i class="nav-icon fas fa-notes-medical"></i>
                <p>{{ __('devtools::common.log_viewer') }}</p>
            </a>
        </li>
    </ul>
</li>
