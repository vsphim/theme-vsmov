@extends('themes::themephim.layout')

@php
    $years = Cache::remember('phim.all-years', setting('site_cache_ttl', 5 * 60), function () {
        return \VsMov\Core\Models\Movie::select('publish_year')
            ->whereNotNull('publish_year')
            ->distinct()
            ->orderBy('publish_year', 'desc')
            ->pluck('publish_year');
    });
@endphp

@section('breadcrumb')
    <ol class="phim-breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/" itemprop="item"><i class="fa fa-home"></i> <span itemprop="name">Trang chủ</span></a>
            <meta itemprop="position" content="1">
        </li>
        <li><i class="fa fa-angle-right"></i></li>
        <li class="is-active">{{ $section_name }}</li>
    </ol>
@endsection

@section('content')
    <section class="phim-catalog">
        <div class="phim-section-heading">
            <h1>{{ $section_name }}</h1>
            <button class="phim-filter-toggle" type="button" onclick="$('.phim-filter').slideToggle(180)">
                <i class="fa fa-sliders"></i> Bộ lọc
            </button>
        </div>

        @include('themes::themephim.inc.catalog_filter')

        @if ($data->count())
            <ul class="phim-movie-grid is-four-columns">
                @foreach ($data as $movie)
                    @php $xClass = 'phim-card'; @endphp
                    @include('themes::themephim.inc.sections_movies_item')
                @endforeach
            </ul>

            <div class="phim-pagination">
                {{ $data->appends(request()->all())->links('themes::themephim.inc.pagination') }}
            </div>
        @else
            <div class="phim-empty">Chưa có phim phù hợp với lựa chọn này.</div>
        @endif
    </section>
@endsection
