@if ($recommendations->count())
    @php
        $heroSlides = $recommendations->take(5)->values();
    @endphp

    <section class="phim-hero" data-phim-hero-slider data-active-slide="0">
        <div class="phim-hero-stage">
            @foreach ($heroSlides as $featured)
                @php
                    $heroTitleLength = \Illuminate\Support\Str::length($featured->name);
                    $heroTitleClass = $heroTitleLength > 75
                        ? 'is-very-long'
                        : ($heroTitleLength > 42 ? 'is-long' : '');
                @endphp
                <article id="phim-hero-slide-{{ $loop->index }}"
                    class="phim-hero-main phim-hero-slide {{ $loop->first ? 'is-active' : '' }}"
                    data-hero-slide="{{ $loop->index }}"
                    aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                    style="background-image:url('{{ $featured->getPosterUrl() }}')">
                    <div class="phim-hero-overlay"></div>
                    <div class="phim-hero-copy">
                        <h1 class="{{ $heroTitleClass }}">{{ $featured->name }}</h1>
                        <div class="phim-hero-meta">
                            <span>IMDb {{ $featured->getRatingStar() }}</span>
                            <span class="is-yellow">HD</span>
                            <span>{{ $featured->publish_year }}</span>
                            @if ($featured->episode_time)<span>{{ $featured->episode_time }}</span>@endif
                            @if ($featured->episode_current)<span>{{ $featured->episode_current }}</span>@endif
                            @if ($featured->language)<span class="is-light">{{ $featured->language }}</span>@endif
                        </div>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($featured->content), 170) }}</p>
                        <div class="phim-hero-actions">
                            <a class="phim-btn phim-btn-primary" href="{{ $featured->getUrl() }}">
                                <i class="fa fa-play-circle"></i> Xem ngay
                            </a>
                            <button type="button" class="phim-favourite-button"
                                data-phim-favourite
                                data-movie-id="{{ $featured->id }}"
                                data-movie-name="{{ $featured->name }}"
                                data-movie-url="{{ $featured->getUrl() }}"
                                data-movie-poster="{{ $featured->getPosterUrl() }}"
                                aria-label="Thêm {{ $featured->name }} vào yêu thích"
                                aria-pressed="false"
                                title="Thêm vào yêu thích">
                                <i class="fa fa-heart"></i>
                            </button>
                            <button type="button" class="phim-share-button"
                                data-phim-share
                                data-share-url="{{ $featured->getUrl() }}"
                                aria-label="Sao chép liên kết {{ $featured->name }}"
                                title="Sao chép liên kết">
                                <i class="fa fa-share"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="phim-hero-thumbs" aria-label="Chọn phim nổi bật">
            @foreach ($heroSlides->slice(1, 4) as $movie)
                <button type="button" class="phim-hero-thumb"
                    data-hero-target="{{ $loop->index + 1 }}"
                    aria-controls="phim-hero-slide-{{ $loop->index + 1 }}"
                    aria-pressed="false"
                    aria-label="Hiển thị {{ $movie->name }}"
                    title="{{ $movie->name }}">
                    <img src="{{ $movie->getPosterUrl() }}" alt="{{ $movie->name }}">
                </button>
            @endforeach
        </div>
    </section>
@endif
