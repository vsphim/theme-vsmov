@php
    $featuredMovie = $item['data']->first();
@endphp

@if ($featuredMovie)
    <section class="phim-section phim-home-style3" style="background-image:url('{{ $featuredMovie->getPosterUrl() }}')">
        <div class="phim-style3-shade"></div>
        <div class="phim-style3-content">
            <span class="phim-style3-eyebrow">{{ $item['label'] }}</span>
            <h2>{{ $featuredMovie->name }}</h2>
            <div class="phim-hero-meta">
                <span>IMDb {{ $featuredMovie->getRatingStar() }}</span>
                <span class="is-yellow">HD</span>
                <span>{{ $featuredMovie->publish_year }}</span>
                @if ($featuredMovie->episode_time)<span>{{ $featuredMovie->episode_time }}</span>@endif
                @if ($featuredMovie->episode_current)<span>{{ $featuredMovie->episode_current }}</span>@endif
                @if ($featuredMovie->language)<span class="is-light">{{ $featuredMovie->language }}</span>@endif
            </div>
            <div class="phim-style3-categories">
                @foreach ($featuredMovie->categories->take(3) as $category)
                    <a href="{{ $category->getUrl() }}">{{ $category->name }}</a>
                @endforeach
            </div>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($featuredMovie->content), 175) }}</p>
            <div class="phim-hero-actions">
                <a class="phim-btn phim-btn-primary" href="{{ $featuredMovie->getUrl() }}">
                    <i class="fa fa-play-circle"></i> Xem ngay
                </a>
                <button type="button" class="phim-favourite-button"
                    data-phim-favourite
                    data-movie-id="{{ $featuredMovie->id }}"
                    data-movie-name="{{ $featuredMovie->name }}"
                    data-movie-url="{{ $featuredMovie->getUrl() }}"
                    data-movie-poster="{{ $featuredMovie->getPosterUrl() }}"
                    aria-label="Thêm {{ $featuredMovie->name }} vào yêu thích"
                    aria-pressed="false"
                    title="Thêm vào yêu thích">
                    <i class="fa fa-heart"></i>
                </button>
            </div>
            <div class="phim-style3-thumbs">
                @foreach ($item['data']->take(5) as $movie)
                    <a href="{{ $movie->getUrl() }}" class="{{ $loop->first ? 'is-active' : '' }}" title="{{ $movie->name }}">
                        <img src="{{ $movie->getPosterUrl() }}" alt="{{ $movie->name }}">
                    </a>
                @endforeach
            </div>
        </div>
        @if ($item['link'] !== '#')
            <a class="phim-style3-more" href="{{ $item['link'] }}">Xem toàn bộ <i class="fa fa-angle-right"></i></a>
        @endif
    </section>
@endif
