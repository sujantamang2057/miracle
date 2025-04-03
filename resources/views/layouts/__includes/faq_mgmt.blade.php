@if (checkMenuAccess('faqCategories', 'cmsAdmin') || checkMenuAccess('faqs', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['faq-category*', 'faq', 'faq/*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['faq-category*', 'faq', 'faq/*'], true) }}">
            <i class="nav-icon fas fa-tag"></i>
            <p>{{ __('common::backend.menu.faq_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('faqCategories', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.faqCategories.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['faq-category*'], true) }}">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>{{ __('cmsadmin::models/faq_categories.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('faqs', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.faqs.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['faq', 'faq/*'], true) }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>{{ __('cmsadmin::models/faqs.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
