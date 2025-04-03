@if (checkMenuAccess('backupCleaner', 'tools') ||
        checkMenuAccess('cleaner', 'tools') ||
        checkMenuAccess('imageCleaner', 'tools') ||
        checkMenuAccess('backup', 'tools') ||
        checkMenuAccess('filemanager', 'tools') ||
        checkMenuAccess('spaceReport', 'tools') ||
        checkMenuAccess('mailTester', 'tools') ||
        checkMenuAccess('imageRegenerator', 'tools') ||
        checkMenuAccess('slugRegenerator', 'tools') ||
        checkMenuAccess('trashDataPurger', 'tools'))
    <li
        class="nav-item {{ setActiveMenuTools(['backup-cleaner*', 'cleaner*', 'image-cleaner*', 'backup', 'backup/*', 'filemanager*', 'space-report*', 'mail-tester*', 'slug-regenerator*', 'image-regenerator*', 'trash-data-purger*']) }}">
        <a href="#"
            class="nav-link {{ setActiveMenuTools(['backup-cleaner*', 'cleaner*', 'backup', 'backup/*', 'image-cleaner*', 'filemanager*', 'space-report*', 'mail-tester*', 'slug-regenerator*', 'image-regenerator*', 'trash-data-purger*'], true) }}">
            <i class="nav-icon fas fa-wrench"></i>
            <p>{{ __('common::backend.menu.tools') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('backupCleaner', 'tools') || checkMenuAccess('cleaner', 'tools') || checkMenuAccess('imageCleaner', 'tools'))
                <li class="nav-item {{ setActiveMenuTools(['cleaner*', 'backup-cleaner*', 'image-cleaner*']) }}">
                    <a href="javascript:void(0);" class="nav-link {{ setActiveMenuTools(['cleaner*', 'backup-cleaner*', 'image-cleaner*'], true) }}">
                        <i class="nav-icon fa fa-eraser"></i>
                        <p>{{ __('tools::common.cleaner') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (checkMenuAccess('cleaner', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.cleaner.index') }}" class="nav-link {{ setActiveMenuTools(['cleaner*'], true) }} pl-5">
                                    <i class="nav-icon fa fa-eraser"></i>
                                    <p>{{ __('tools::common.app_cleaner') }}</p>
                                </a>
                            </li>
                        @endif
                        @if (checkMenuAccess('backupCleaner', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.backupCleaner.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['backup-cleaner*'], true) }} pl-5">
                                    <i class="nav-icon fa fa-quidditch"></i>
                                    <p>{{ __('tools::common.backup_cleaner') }}</p>
                                </a>
                            </li>
                        @endif
                        @if (checkMenuAccess('imageCleaner', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.imageCleaner.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['image-cleaner*'], true) }} pl-5">
                                    <span style="font-size: 16px">
                                        <i class="fas fa-broom" style="position: relative; left: 2px;"></i>
                                        <i class="fas fa-image" style="position: relative; top: 10px; left: -12px; font-size:10px;"></i>
                                    </span>
                                    <p>{{ __('tools::common.image_cleaner') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (checkMenuAccess('backup', 'tools') || checkMenuAccess('trashDataPurger', 'tools'))
                <li class="nav-item {{ setActiveMenuTools(['backup', 'backup/*', 'trash-data-purger*']) }}">
                    <a href="javascript:void(0);" class="nav-link {{ setActiveMenuTools(['backup', 'backup/*', 'trash-data-purger*'], true) }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>{{ __('tools::common.data_mgmt') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (checkMenuAccess('backup', 'tools') || checkMenuAccess('trashDataPurger', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.backup.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['backup', 'backup/*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>{{ __('tools::common.db_backup') }}</p>
                                </a>
                            </li>
                        @endif
                        @if (checkMenuAccess('trashDataPurger', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.trashDataPurger.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['trash-data-purger*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-backspace"></i>
                                    <p>{{ __('tools::common.trash_data_purger') }} </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (checkMenuAccess('filemanager', 'tools') || checkMenuAccess('spaceReport', 'tools'))
                <li class="nav-item {{ setActiveMenuTools(['filemanager*', 'space-report*']) }}">
                    <a href="javascript:void(0);" class="nav-link {{ setActiveMenuTools(['filemanager*', 'space-report*'], true) }}">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>{{ __('tools::common.file_mgmt') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (checkMenuAccess('filemanager', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.filemanager.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['filemanager*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>{{ __('tools::common.file_manager') }}</p>
                                </a>
                            </li>
                        @endif
                        @if (checkMenuAccess('spaceReport', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.spaceReport.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['space-report*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-hdd"></i>
                                    <p>{{ __('tools::common.space_report') }} </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (checkMenuAccess('mailTester', 'tools'))
                <li class="nav-item">
                    <a href="{{ route('tools.mailTester.index') }}" class="nav-link {{ setActiveMenuTools(['mail-tester*'], true) }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>{{ __('tools::common.mail_tester') }}</p>
                    </a>
                </li>
            @endif

            @if (checkMenuAccess('imageRegenerator', 'tools') || checkMenuAccess('slugRegenerator', 'tools'))
                <li class="nav-item {{ setActiveMenuTools(['slug-regenerator*', 'image-regenerator*']) }}">
                    <a href="javascript:void(0);" class="nav-link {{ setActiveMenuTools(['slug-regenerator*', 'image-regenerator*'], true) }}">
                        <i class="nav-icon fas fa-redo-alt"></i>
                        <p>{{ __('tools::common.regenerator') }} <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (checkMenuAccess('imageRegenerator', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.imageRegenerator.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['image-regenerator*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-image"></i>
                                    <p>{{ __('tools::common.image_regenerator') }}</p>
                                </a>
                            </li>
                        @endif

                        @if (checkMenuAccess('slugRegenerator', 'tools'))
                            <li class="nav-item">
                                <a href="{{ route('tools.slugRegenerator.index') }}"
                                    class="nav-link {{ setActiveMenuTools(['slug-regenerator*'], true) }} pl-5">
                                    <i class="nav-icon fas fa-link"></i>
                                    <p>{{ __('tools::common.slug_regenerator') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
