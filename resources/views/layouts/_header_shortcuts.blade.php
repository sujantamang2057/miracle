@if (checkCmsAdminPermissionList(['banners.create', 'banners.store']) ||
        checkCmsAdminPermissionList(['pages.create', 'pages.store']) ||
        checkCmsAdminPermissionList(['news.create', 'news.store']) ||
        checkCmsAdminPermissionList(['posts.create', 'posts.store']) ||
        checkCmsAdminPermissionList(['blogs.create', 'blogs.store']) ||
        checkCmsAdminPermissionList(['albums.create', 'albums.store']) ||
        checkCmsAdminPermissionList(['testimonials.create', 'testimonials.store']))
    <li class="nav-item dropdown quick-menu">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="true">
            <i class="fa fa-plus-square"></i> <span>{{ __('common::backend.menu.quick_menu') }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @if (checkCmsAdminPermissionList(['banners.create', 'banners.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.banners.create') }}" class="dropdown-item">
                    <i class="fas fa-image mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/banners.singular') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['pages.create', 'pages.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.pages.create') }}" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/pages.singular') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['news.create', 'news.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.news.create') }}" class="dropdown-item">
                    <i class="fas fa-newspaper mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/news.singular') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['posts.create', 'posts.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.posts.create') }}" class="dropdown-item">
                    <i class="fas fa-pen mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/posts.singular') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['blogs.create', 'blogs.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.blogs.create') }}" class="dropdown-item">
                    <i class="fas fa-bold mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/blogs.singular') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['albums.create', 'albums.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.albums.create') }}" class="dropdown-item">
                    <i class="fas fa-images mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/albums.album') }}
                </a>
            @endif
            @if (checkCmsAdminPermissionList(['testimonials.create', 'testimonials.store']))
                <div class="dropdown-divider"></div>
                <a href="{{ route('cmsadmin.testimonials.create') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> {{ __('common::crud.create') }} {{ __('cmsadmin::models/testimonials.singular') }}
                </a>
            @endif
        </div>
    </li>
@endif
