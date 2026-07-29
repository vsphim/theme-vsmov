@php $region = $movie->regions->first(); @endphp
<li class="phim-landscape-card">
    <a class="phim-landscape-image" href="{{ $movie->getUrl() }}" title="{{ $movie->name }}">
        <img class="lazy" data-original="{{ $movie->getPosterUrl() }}" alt="{{ $movie->name }}">
        <span><i class="fa fa-play"></i> Xem ngay</span>
    </a>
    <h3><a href="{{ $movie->getUrl() }}">{{ $movie->name }}</a></h3>
    <div class="phim-card-meta">
        <span class="phim-accent">{{ $region ? $region->name : 'Đang cập nhật' }}</span>
        <i>•</i><span>{{ $movie->publish_year }}</span>
        <span class="phim-views"><i class="fa fa-eye"></i> {{ number_format($movie->view_total) }}</span>
    </div>
    <div class="phim-badges">
        @if ($movie->episode_current)<span>{{ $movie->episode_current }}</span>@endif
        @if ($movie->language)<span class="is-light">{{ $movie->language }}</span>@endif
    </div>
</li>
