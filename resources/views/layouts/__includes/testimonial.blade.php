@if (checkMenuAccess('testimonials', 'cmsAdmin'))
    <li class="nav-item">
        <a href="{{ route('cmsadmin.testimonials.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['testimonial*'], true) }}">
            <i class="nav-icon fas fa-user"></i>
            <p>{{ __('cmsadmin::models/testimonials.singular') }} </p>
        </a>
    </li>
@endif
