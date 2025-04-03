@if (checkMenuAccess('albums', 'cmsAdmin') || checkMenuAccess('videoAlbums', 'cmsAdmin'))
    <li class="nav-item {{ setActiveMenuCmsAdmin(['album*', 'video-album*']) }}">
        <a href="#" class="nav-link {{ setActiveMenuCmsAdmin(['album*', 'video-album*'], true) }}">
            <i class="nav-icon fas fa-photo-video"></i>
            <p>{{ __('common::backend.menu.gallery_mgmt') }}<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @if (checkMenuAccess('albums', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.albums.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['album*'], true) }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>{{ __('cmsadmin::models/albums.singular') }}</p>
                    </a>
                </li>
            @endif
            @if (checkMenuAccess('videoAlbums', 'cmsAdmin'))
                <li class="nav-item">
                    <a href="{{ route('cmsadmin.videoAlbums.index') }}" class="nav-link {{ setActiveMenuCmsAdmin(['video-album*'], true) }}">
                        <i class="nav-icon fas fa-video"></i>
                        <p>{{ __('cmsadmin::models/video_albums.singular') }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
