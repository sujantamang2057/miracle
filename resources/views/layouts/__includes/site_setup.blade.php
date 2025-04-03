@if (checkMenuAccess('menus', 'cmsAdmin') ||
        checkMenuAccess('blocks', 'cmsAdmin') ||
        checkMenuAccess('banners', 'cmsAdmin') ||
        checkMenuAccess('pages', 'cmsAdmin') ||
        checkMenuAccess('seos', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['menu*', 'block*', 'page*', 'banner*', 'seo*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['menu*', 'block*', 'page*', 'banner*', 'seo*'], true) }}">
            <i class="nav-icon fas fa-sitemap"></i>
            <p>{{ __('common::backend.menu.site_setup') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('menus', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.menus.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['menu*'], true) }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>{{ __('cmsadmin::models/menus.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('blocks', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.blocks.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['block*'], true) }}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>{{ __('cmsadmin::models/blocks.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('banners', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.banners.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['banner*'], true) }}">
                        <i class="nav-icon fas fa-image"></i>
                        <p>{{ __('cmsadmin::models/banners.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('pages', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.pages.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['page*'], true) }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>{{ __('cmsadmin::models/pages.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('seos', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.seos.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['seo*'], true) }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>{{ __('cmsadmin::models/seos.seo_setting') }} </p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
