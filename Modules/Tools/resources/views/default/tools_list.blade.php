<div class="tools-default-index">
    <div class="container-fluid px-1">
        <section class="content px-0">
            @if (checkMenuAccess('backupCleaner', 'tools') || checkMenuAccess('cleaner', 'tools') || checkMenuAccess('imageCleaner', 'tools'))
                <div class="tools-row">
                    <h4 class="mt-1 px-2">
                        <i class="fa fa-eraser mr-1"></i>{{ __('tools::common.cleaner') }}
                    </h4>
                    <div class="row mx-0">
                        @if (checkMenuAccess('cleaner', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.cleaner.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red elevation-1"><i class="fa fa-eraser"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.app_cleaner') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (checkMenuAccess('backupCleaner', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.backupCleaner.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-navy elevation-1"><i class="fa fa-quidditch"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.backup_cleaner') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (checkMenuAccess('imageCleaner', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.imageCleaner.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-orange elevation-1" style="font-size: 16px">
                                            <i class="fas fa-broom" style="position: relative; left: 2px;"></i>
                                            <i class="fas fa-image" style="position: relative; top: 12px; left: -8px; font-size:10px;"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.image_cleaner') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if (checkMenuAccess('backup', 'tools') || checkMenuAccess('trashDataPurger', 'tools'))
                <div class="tools-row">
                    <h4 class="mt-1 px-2">
                        <i class="fa fa-database mr-1"></i>{{ __('tools::common.data_mgmt') }}
                    </h4>
                    <div class="row mx-0">
                        @if (checkMenuAccess('backup', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.backup.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-teal elevation-1"><i class="fa fa-database"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.db_backup') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (checkMenuAccess('trashDataPurger', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.trashDataPurger.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red elevation-1"><i class="nav-icon fa fa-backspace"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::trash_data_purgers.purge_trash_data') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if (checkMenuAccess('filemanager', 'tools') || checkMenuAccess('spaceReport', 'tools'))
                <div class="tools-row">
                    <h4 class="mt-1 px-2">
                        <i class="fas fa-copy mr-1"></i>{{ __('tools::common.file_mgmt') }}
                    </h4>

                    <div class="row mx-0">
                        @if (checkMenuAccess('filemanager', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.filemanager.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-fuchsia elevation-1"><i class="fas fa-copy"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.file_manager') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (checkMenuAccess('spaceReport', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.spaceReport.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-hdd"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.space_report') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if (checkMenuAccess('mailTester', 'tools'))
                <div class="tools-row">
                    <h4 class="mt-1 px-2">
                        <i class="fas fa-envelope mr-1"></i>{{ __('tools::common.mail_tester') }}
                    </h4>
                    <div class="row mx-0">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('tools.mailTester.index') }}" class="text-white">
                                <div class="info-box">
                                    <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-envelope"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ __('tools::common.mail_tester') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if (checkMenuAccess('imageRegenerator', 'tools') || checkMenuAccess('slugRegenerator', 'tools'))
                <div class="tools-row">
                    <h4 class="mt-1 px-2"><i class="fas fa-redo-alt mr-1"></i>{{ __('tools::common.regenerator') }}
                    </h4>
                    <div class="row mx-0">
                        @if (checkMenuAccess('imageRegenerator', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.imageRegenerator.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-image"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.image_regenerator') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if (checkMenuAccess('imageRegenerator', 'tools'))
                            <div class="col-6 col-md-3">
                                <a href="{{ route('tools.slugRegenerator.index') }}" class="text-white">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-gray elevation-1"><i class="fas fa-link"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('tools::common.slug_regenerator') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
            @endif
        </section>
    </div>
</div>
