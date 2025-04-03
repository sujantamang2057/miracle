@if (checkMenuAccess('newsCategories', 'cmsAdmin') || checkMenuAccess('news', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['news-category*', 'news', 'news/*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['news-category*', 'news', 'news/*'], true) }}">
            <i class="nav-icon fas fa-newspaper"></i>
            <p>{{ __('common::backend.menu.news_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('newsCategories', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.newsCategories.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['news-category*'], true) }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('cmsadmin::models/news_categories.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('news', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.news.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['news', 'news/*'], true) }}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>{{ __('cmsadmin::models/news.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
