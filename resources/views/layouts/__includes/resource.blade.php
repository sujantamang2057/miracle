@if (checkMenuAccess('resources', 'cmsAdmin'))
    <li class="nav-item">
        <a href="{{ route('cmsadmin.resources.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['resource*'], true) }}">
            <i class="nav-icon fab fa-dropbox"></i>
            <p>{{ __('cmsadmin::models/resources.singular') }}</p>
        </a>
    </li>
@endif
