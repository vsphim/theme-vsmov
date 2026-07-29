<section class="phim-section phim-home-style4">
    <div class="phim-section-heading">
        <h2>{{ $item['label'] }}</h2>
        <a href="{{ $item['link'] }}">Xem toàn bộ <i class="fa fa-angle-right"></i></a>
    </div>
    <ul class="phim-style4-grid">
        @foreach ($item['data'] as $movie)
            <li>
                <a class="phim-style4-image" href="{{ $movie->getUrl() }}">
                    <img class="lazy" data-original="{{ $movie->getPosterUrl() }}" alt="{{ $movie->name }}">
                    <span class="phim-style4-trailer">{{ $movie->status === 'trailer' ? 'Trailer' : ($movie->episode_current ?: 'Sắp chiếu') }}</span>
                    <span class="phim-style4-hd">HD</span>
                </a>
                <h3><a href="{{ $movie->getUrl() }}">{{ $movie->name }}</a></h3>
                <a class="phim-style4-watch" href="{{ $movie->getUrl() }}"><i class="fa fa-play-circle"></i> Xem ngay</a>
            </li>
        @endforeach
    </ul>
</section>
