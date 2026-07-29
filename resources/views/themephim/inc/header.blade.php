@php
    $logo = setting('site_logo', '');
    $brand = setting('site_brand', '');
    $pageTitle = isset($title) ? $title : setting('site_homepage_title', '');
@endphp

<header class="phim-header">
    <div class="phim-container phim-header-inner">
        <button class="phim-nav-toggle" type="button" aria-label="Mở menu">
            <span></span><span></span><span></span>
        </button>

        <a class="phim-logo" href="/" title="{{ $pageTitle }}">
            @if ($logo)
                {!! $logo !!}
            @elseif ($brand)
                {!! $brand !!}
            @else
                <span>VS</span><b>M</b><span>OV</span>
            @endif
        </a>

        <nav class="phim-nav" aria-label="Điều hướng chính">
            @foreach ($menu as $item)
                <div class="phim-nav-item {{ request()->url() === url($item['link']) ? 'is-active' : '' }}">
                    <a href="{{ count($item['children']) ? 'javascript:void(0)' : $item['link'] }}"
                        title="{{ $item['name'] }}">
                        {{ $item['name'] }}
                        @if (count($item['children']))
                            <i class="fa fa-caret-down"></i>
                        @endif
                    </a>
                    @if (count($item['children']))
                        <div class="phim-subnav">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $child['link'] }}" title="{{ $child['name'] }}">{{ $child['name'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <form class="phim-search" method="GET" action="/">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm kiếm phim, diễn viên..." autocomplete="off">
            <button type="submit" aria-label="Tìm kiếm"><i class="fa fa-search"></i></button>
        </form>
        <button class="phim-search-toggle" type="button" aria-label="Mở tìm kiếm">
            <i class="fa fa-search"></i>
        </button>
    </div>
</header>
