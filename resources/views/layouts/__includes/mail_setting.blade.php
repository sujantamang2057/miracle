@if (checkMenuAccess('mailerSettings', 'sys') || checkMenuAccess('emailTemplates', 'sys'))
    <li class="nav-item {{ setActiveMenuSys(['mailer-setting*', 'email-template*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuSys(['mailer-setting*', 'email-template*'], true) }}">
            <i class="nav-icon fas fa-envelope"></i>
            <p>{{ __('common::backend.menu.mail_setting') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('mailerSettings', 'sys'))
                <li class="nav-item">
                    <a href="{{ route('sys.mailerSettings.index') }}" class="nav-link {{ setActiveMenuSys(['mailer-setting*'], true) }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>{{ __('sys::models/mailer_settings.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('emailTemplates', 'sys'))
                <li class="nav-item">
                    <a href="{{ route('sys.emailTemplates.index') }}" class="nav-link {{ setActiveMenuSys(['email-template*'], true) }}">
                        <i class="nav-icon fa fa-envelope"></i>
                        <p>{{ __('sys::models/email_templates.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
