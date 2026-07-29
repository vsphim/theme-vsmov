<section class="phim-section phim-home-style1">
    <div class="phim-section-heading is-centered">
        <h2>{{ $item['label'] }}</h2>
    </div>
    <ul class="phim-movie-grid is-five-columns">
        @foreach ($item['data'] as $movie)
            @php
                $xClass = 'phim-card';
                $rank = $loop->iteration;
            @endphp
            @include('themes::themephim.inc.sections_movies_item')
        @endforeach
    </ul>
    @if ($item['link'] !== '#')
        <div class="phim-section-more"><a href="{{ $item['link'] }}">Xem toàn bộ <i class="fa fa-angle-right"></i></a></div>
    @endif
</section>
