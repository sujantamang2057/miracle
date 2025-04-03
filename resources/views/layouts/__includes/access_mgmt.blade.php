@if (checkMenuAccess('users', 'sys'))
    <li class="nav-item {{ setActiveMenuSys(['user*', 'rbac*', 'role*', 'permission*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuSys(['user*', 'rbac*', 'role*', 'permission*'], true) }}">
            <i class="nav-icon fas fa-key"></i>
            <p>{{ __('common::backend.menu.access_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('users', 'sys'))
                <li class="nav-item">
                    <a href="{{ route('sys.users.index') }}" class="nav-link {{ setActiveMenuSys(['user*'], true) }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ __('sys::models/users.singular') }}</p>
                    </a>
                </li>
            @endif
            @hasrole(ROLE_MASTER)
                <li class="nav-item">
                    <a href="{{ route('sys.rbac.index') }}" class="nav-link {{ setActiveMenuSys(['rbac*', 'role*', 'permission*'], true) }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>{{ __('sys::models/roles.text.rbac') }}</p>
                    </a>
                </li>
            @endhasrole
        </ul>
    </li>
@endif
