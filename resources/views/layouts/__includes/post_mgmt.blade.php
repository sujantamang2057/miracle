@if (checkMenuAccess('postCategories', 'cmsAdmin') || checkMenuAccess('posts', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['post-category*', 'post', 'post/*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['post-category*', 'post', 'post/*'], true) }}">
            <i class="nav-icon fas fa-pen"></i>
            <p>{{ __('common::backend.menu.post_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('postCategories', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.postCategories.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['post-category*'], true) }}">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>{{ __('cmsadmin::models/post_categories.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('posts', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.posts.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['post', 'post/*'], true) }}">
                        <i class="nav-icon fas fa-pen"></i>
                        <p>{{ __('cmsadmin::models/posts.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
