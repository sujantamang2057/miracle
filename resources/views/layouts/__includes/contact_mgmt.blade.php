@if (checkMenuAccess('contacts', 'cmsAdmin'))
    <li class="nav-item">
        <a href="{{ route('cmsadmin.contacts.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['contact*'], true) }}">
            <i class="nav-icon fas fa-envelope"></i>
            <p>{{ __('cmsadmin::models/contacts.singular') }} </p>
        </a>
    </li>
@endif
