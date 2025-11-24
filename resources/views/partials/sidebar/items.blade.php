@if ($item && $item->authorize(auth()->user()) && $item->getTitle() !== 'Activity Log' && $item->getTitle() !== 'Announcements')
    <li>
        @if(!$item->isDropdown())
            <a href="{{ $item->getHref() }}" class="waves-effect {{ Request::is($item->getActivePath()) ? 'active' : '' }}">
                @if ($item->getIcon())
                    <i class="{{ $item->getIcon() }}"></i>
                @endif
                <span key="t-{{ Str::slug($item->getTitle()) }}">{{ $item->getTitle() }}</span>
            </a>
        @else
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                @if ($item->getIcon())
                    <i class="{{ $item->getIcon() }}"></i>
                @endif
                <span key="t-{{ Str::slug($item->getTitle()) }}">{{ $item->getTitle() }}</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                @foreach ($item->children() as $child)
                    @include('partials.sidebar.items', ['item' => $child])
                @endforeach
            </ul>
        @endif
    </li>
@endif