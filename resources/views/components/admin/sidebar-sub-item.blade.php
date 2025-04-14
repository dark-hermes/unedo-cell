<ul class="submenu">
    @foreach ($items as $item)
        <li class="submenu-item {{ $item['active'] ? 'active' : '' }}">
            <a href="{{ $item['href'] }}" class="submenu-link">{{ $item['label'] }}</a>
        </li>
    @endforeach
</ul>
