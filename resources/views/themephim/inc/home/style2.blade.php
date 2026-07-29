<section class="phim-section phim-section-panel phim-home-style2">
    <div class="phim-section-heading">
        <h2>{{ $item['label'] }}</h2>
        <a href="{{ $item['link'] }}">Xem toàn bộ <i class="fa fa-angle-right"></i></a>
    </div>
    <ul class="phim-landscape-grid">
        @foreach ($item['data'] as $movie)
            @include('themes::themephim.inc.landscape_movie')
        @endforeach
    </ul>
</section>
