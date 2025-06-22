<!-- Announcement Bar Component -->
@if (isset($announcements) && count($announcements) > 0)
    <div class="announcement-container">
        <div class="announcement-wrapper">
            @foreach ($announcements as $announcement)
                <div class="announcement-item">
                    <i class="fa-solid fa-bell"></i>
                    <span>{{ $announcement->{'name_' . app()->getLocale()} }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
