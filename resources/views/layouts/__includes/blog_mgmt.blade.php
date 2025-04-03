@if (checkMenuAccess('blogCategories', 'cmsAdmin') || checkMenuAccess('blogs', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['blog-category*', 'blog', 'blog/*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['blog-category*', 'blog', 'blog/*'], true) }}">
            <i class="nav-icon fas fa-bold"></i>
            <p>{{ __('common::backend.menu.blogs_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('blogCategories', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.blogCategories.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['blog-category*'], true) }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('cmsadmin::models/blog_categories.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('blogs', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.blogs.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['blog', 'blog/*'], true) }}">
                        <i class="nav-icon fas fa-bold"></i>
                        <p>{{ __('cmsadmin::models/blogs.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
