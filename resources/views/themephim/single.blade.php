@extends('themes::themephim.layout')

@php
    $region = $currentMovie->regions->first();
    $watchUrl = '#';
    $personPlaceholder = '/themes/phim/images/person-placeholder.svg';

    if (!$currentMovie->is_copyright && $currentMovie->episodes->count() && $currentMovie->episodes->first()->link) {
        $watchUrl = $currentMovie->episodes
            ->sortBy([['server', 'asc']])
            ->groupBy('server')
            ->first()
            ->sortByDesc('name', SORT_NATURAL)
            ->groupBy('name')
            ->last()
            ->sortByDesc('type')
            ->first()
            ->getUrl();
    }
@endphp

@section('breadcrumb')
    <ol class="phim-breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li><a href="/"><i class="fa fa-home"></i> Trang chủ</a></li>
        @if ($region)
            <li><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ $region->getUrl() }}">{{ $region->name }}</a></li>
        @endif
        <li><i class="fa fa-angle-right"></i></li>
        <li class="is-active">{{ $currentMovie->name }}</li>
    </ol>
@endsection

@section('content')
    <article class="phim-detail" itemscope itemtype="https://schema.org/Movie">
        <h1 itemprop="name">{{ $currentMovie->name }}</h1>

        <div class="phim-detail-layout">
            <div class="phim-detail-main">
                <section class="phim-detail-hero" style="background-image:url('{{ $currentMovie->getPosterUrl() }}')">
                    <div class="phim-detail-shade"></div>
                    <img class="phim-schema-image" itemprop="image" src="{{ $currentMovie->getThumbUrl() }}"
                        alt="{{ $currentMovie->name }}">
                    <div class="phim-detail-copy">
                        <h2>{{ $currentMovie->origin_name ?: $currentMovie->name }}</h2>
                        <div class="phim-hero-meta">
                            <span>IMDb {{ $currentMovie->getRatingStar() }}</span>
                            <span class="is-yellow">HD</span>
                            <span>{{ $currentMovie->publish_year }}</span>
                            @if ($currentMovie->episode_time)<span>{{ $currentMovie->episode_time }}</span>@endif
                            @if ($currentMovie->episode_current)<span>{{ $currentMovie->episode_current }}</span>@endif
                            @if ($currentMovie->language)<span class="is-light">{{ $currentMovie->language }}</span>@endif
                        </div>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($currentMovie->content), 230) }}</p>
                        <div class="phim-hero-actions">
                            <a class="phim-btn phim-btn-primary" href="{{ $watchUrl }}">
                                <i class="fa fa-play-circle"></i> Xem ngay
                            </a>
                            <button type="button" class="phim-favourite-button"
                                data-phim-favourite
                                data-movie-id="{{ $currentMovie->id }}"
                                data-movie-name="{{ $currentMovie->name }}"
                                data-movie-url="{{ $currentMovie->getUrl() }}"
                                data-movie-poster="{{ $currentMovie->getPosterUrl() }}"
                                aria-label="Thêm {{ $currentMovie->name }} vào yêu thích"
                                aria-pressed="false"
                                title="Thêm vào yêu thích">
                                <i class="fa fa-heart"></i>
                            </button>
                            <button type="button" class="phim-share-button"
                                data-phim-share
                                data-share-url="{{ $currentMovie->getUrl() }}"
                                aria-label="Sao chép liên kết {{ $currentMovie->name }}"
                                title="Sao chép liên kết">
                                <i class="fa fa-share"></i>
                            </button>
                        </div>
                    </div>
                </section>

                @if ($currentMovie->type === 'series' && !$currentMovie->is_copyright && $currentMovie->episodes->count())
                    <section class="phim-latest-episodes">
                        <h2>Tập mới nhất</h2>
                        <div>
                            @foreach ($currentMovie->episodes->sortByDesc('name', SORT_NATURAL)->unique('name')->take(8) as $movieEpisode)
                                @php
                                    $latestEpisodeName = \Illuminate\Support\Str::startsWith(\Illuminate\Support\Str::lower($movieEpisode->name), 'tập')
                                        ? $movieEpisode->name
                                        : 'Tập ' . $movieEpisode->name;
                                @endphp
                                <a href="{{ $movieEpisode->getUrl() }}">{{ $latestEpisodeName }}</a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="phim-people">
                <h2>Đạo diễn</h2>
                <div class="phim-people-grid">
                    @forelse ($currentMovie->directors->take(3) as $director)
                        <a href="{{ $director->getUrl() }}">
                            <span>
                                <img src="{{ $director->thumb_url ?: $personPlaceholder }}"
                                    onerror="this.onerror=null;this.src='{{ $personPlaceholder }}';"
                                    loading="lazy"
                                    alt="{{ $director->name }}">
                            </span>
                            <small>{{ $director->name }}</small>
                        </a>
                    @empty
                        <span class="phim-muted">Đang cập nhật</span>
                    @endforelse
                </div>

                <h2>Diễn viên</h2>
                <div class="phim-people-grid is-three">
                    @forelse ($currentMovie->actors->take(9) as $actor)
                        <a href="{{ $actor->getUrl() }}">
                            <span>
                                <img src="{{ $actor->thumb_url ?: $personPlaceholder }}"
                                    onerror="this.onerror=null;this.src='{{ $personPlaceholder }}';"
                                    loading="lazy"
                                    alt="{{ $actor->name }}">
                            </span>
                            <small>{{ $actor->name }}</small>
                        </a>
                    @empty
                        <span class="phim-muted">Đang cập nhật</span>
                    @endforelse
                </div>

                <dl class="phim-detail-taxonomy">
                    <dt>Quốc gia</dt>
                    <dd>
                        @foreach ($currentMovie->regions as $movieRegion)
                            <a href="{{ $movieRegion->getUrl() }}">{{ $movieRegion->name }}</a>{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </dd>
                    <dt>Thể loại</dt>
                    <dd>
                        @forelse ($currentMovie->categories as $category)
                            <a href="{{ $category->getUrl() }}">{{ $category->name }}</a>{{ $loop->last ? '' : ', ' }}
                        @empty
                            <span class="phim-muted">Đang cập nhật</span>
                        @endforelse
                    </dd>
                    <dt>Từ khóa</dt>
                    <dd>
                        @foreach ($currentMovie->tags->take(6) as $tag)
                            <a href="{{ $tag->getUrl() }}">{{ $tag->name }}</a>{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </dd>
                </dl>
            </aside>
        </div>

        <section class="phim-detail-description">
            <div class="phim-section-heading">
                <h2>Nội dung phim</h2>
            </div>
            <div>{!! $currentMovie->content !!}</div>

            @if ($currentMovie->notify)
                <p class="phim-note"><b>Ghi chú:</b> {{ strip_tags($currentMovie->notify) }}</p>
            @endif
            @if ($currentMovie->showtimes)
                <p class="phim-note"><b>Lịch chiếu:</b> {!! $currentMovie->showtimes !!}</p>
            @endif

            <div class="phim-rating box-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                <div id="star" data-score="{{ $currentMovie->getRatingStar() }}"></div>
                <span><b id="average" itemprop="ratingValue">{{ $currentMovie->getRatingStar() }}</b>/10 ·
                    <span id="rate_count" itemprop="ratingCount">{{ $currentMovie->getRatingCount() }}</span> lượt đánh giá</span>
                <span id="hint"></span>
                <meta itemprop="bestRating" content="10">
            </div>
        </section>

        @if ($movie_related->count())
            <section class="phim-section">
                <div class="phim-section-heading is-centered"><h2>Có thể bạn sẽ thích</h2></div>
                <ul class="phim-movie-grid is-five-columns">
                    @foreach ($movie_related->take(10) as $movie)
                        @php $xClass = 'phim-card'; @endphp
                        @include('themes::themephim.inc.sections_movies_item')
                    @endforeach
                </ul>
            </section>
        @endif

    </article>
@endsection

@push('scripts')
    <script>
        const URL_POST_RATING = '{{ route('movie.rating', ['movie' => $currentMovie->slug]) }}';
    </script>
    <script src="/themes/phim/js/filmdetail.js?v=1.0.0"></script>
@endpush
