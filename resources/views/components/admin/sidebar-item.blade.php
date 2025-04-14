<li class="sidebar-item {{ $active ? 'active' : '' }} {{ $hasSub ? 'has-sub' : '' }}">
    <a href="{{ $href }}" class="sidebar-link">
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $label }}</span>
    </a>

    @if ($hasSub)
        {{ $submenu ?? '' }}
    @endif
</li>
