@if (checkMenuAccess('siteSettings', 'sys') || checkMenuAccess('sns', 'sys') || checkMenuAccess('cspHeaders', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuSys(['site-setting*', 'sns*']) }} {{ setActiveMenuCmsAdmin(['csp-header*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuSys(['site-setting*', 'sns*'], true) }} {{ setActiveMenuCmsAdmin(['csp-header*'], true) }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>{{ __('common::backend.menu.system_setting') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('siteSettings', 'sys'))
                <li class="nav-item">
                    <a href="{{ route('sys.siteSettings.index') }}" class="nav-link {{ setActiveMenuSys(['site-setting*'], true) }}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            <p>{{ __('sys::models/site_settings.singular') }}</p>
                        </p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('sns', 'sys'))
                <li class="nav-item">
                    <a href="{{ route('sys.sns.index') }}" class="nav-link {{ setActiveMenuSys(['sns*'], true) }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            <p>{{ __('sys::models/sns.singular') }}</p>
                        </p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('cspHeaders', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.cspHeaders.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['csp-header*'], true) }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>{{ __('cmsadmin::models/csp_headers.singular') }} </p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
