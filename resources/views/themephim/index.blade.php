@extends('themes::themephim.layout')

@php
    use VsMov\Core\Models\Movie;

    $recommendations = Cache::remember('phim.movies.recommendations', setting('site_cache_ttl', 5 * 60), function () {
        return Movie::where('is_recommended', true)
            ->orderBy('updated_at', 'desc')
            ->limit(max(5, get_theme_option('recommendations_limit', 10)))
            ->get();
    });

    if (!$recommendations->count()) {
        $recommendations = Movie::orderBy('updated_at', 'desc')->limit(5)->get();
    }

    $latestConfig = (string) get_theme_option('latest', '');
    $latestCacheKey = 'phim.movies.latest.' . md5($latestConfig);

    $data = Cache::remember($latestCacheKey, setting('site_cache_ttl', 5 * 60), function () use ($latestConfig) {
        $lists = preg_split('/[\n\r]+/', $latestConfig);
        $sections = [];
        $styles = ['style1', 'style2', 'style3', 'style4'];
        $sortFields = ['created_at', 'updated_at', 'publish_year', 'view_total', 'view_day', 'view_week', 'view_month', 'name'];

        foreach ($lists as $list) {
            if (!trim($list)) {
                continue;
            }

            $columns = array_pad(explode('|', trim($list)), 9, '');
            [$label, $relation, $field, $value, $limit, $link, $style, $sortField, $sortDirection] = $columns;

            $style = strtolower(trim($style));
            $style = preg_match('/^[1-4]$/', $style) ? 'style' . $style : str_replace(['-', '_', ' '], '', $style);
            $style = in_array($style, $styles, true) ? $style : 'style2';
            $sortField = in_array($sortField, $sortFields, true) ? $sortField : 'updated_at';
            $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
            $limit = min(50, max(1, (int) ($limit ?: 8)));

            try {
                $movies = Movie::when($relation && $field, function ($query) use ($relation, $field, $value) {
                    $query->whereHas($relation, function ($relationQuery) use ($field, $value) {
                        $relationQuery->where($field, $value);
                    });
                })->when(!$relation && $field, function ($query) use ($field, $value) {
                    $query->where($field, $value);
                })->orderBy($sortField, $sortDirection)->limit($limit)->get();

                $sections[] = [
                    'label' => $label ?: 'Phim mới cập nhật',
                    'data' => $movies,
                    'link' => $link ?: '#',
                    'style' => $style,
                ];
            } catch (\Exception $exception) {
            }
        }

        return $sections;
    });

    $topicTitle = trim((string) get_theme_option('hot_topics_title', 'Chủ đề hot')) ?: 'Chủ đề hot';
    $topicItems = collect(preg_split('/[\r\n]+/', (string) get_theme_option('hot_topics', '')))
        ->filter(function ($line) {
            return trim($line) !== '';
        })
        ->map(function ($line) {
            $columns = array_pad(explode('|', trim($line)), 3, '');

            return [
                'name' => trim($columns[0]),
                'link' => trim($columns[1]) ?: '#',
                'image' => trim($columns[2]),
            ];
        })
        ->filter(function ($topic) {
            return $topic['name'] !== '';
        })
        ->values()
        ->take(8);
@endphp

@section('slider_recommended')
    @include('themes::themephim.inc.slider_recommended')
@endsection

@section('content')
    @if ($topicItems->count())
        <section class="phim-topics">
            <h2>{{ $topicTitle }}</h2>
            <div class="phim-topic-grid">
                @foreach ($topicItems as $topic)
                    @php
                        $topicMovie = $recommendations->get($loop->index % max(1, $recommendations->count()));
                        $topicImage = $topic['image'] ?: ($topicMovie ? $topicMovie->getPosterUrl() : '');
                    @endphp
                    <a href="{{ $topic['link'] }}"
                        @if ($topicImage) style="background-image:linear-gradient(90deg, rgba(18,20,31,.42), rgba(18,20,31,.12)), url('{{ $topicImage }}')" @endif>
                        {{ $topic['name'] }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @foreach ($data as $item)
        @include('themes::themephim.inc.sections_movies')
    @endforeach
@endsection
