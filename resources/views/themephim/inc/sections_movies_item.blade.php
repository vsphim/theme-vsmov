@php
    $region = $movie->regions->first();
    $cardClass = isset($xClass) ? $xClass : 'phim-card';
    $rankNumber = isset($rank) ? $rank : null;
@endphp

<li class="{{ $cardClass }} phim-card">
    <a class="phim-card-poster" href="{{ $movie->getUrl() }}" title="{{ $movie->name }}">
        <img class="lazy" data-original="{{ $movie->getThumbUrl() }}" alt="{{ $movie->name }}"
            title="{{ $movie->name }}">
        @if ($rankNumber)
            <span class="phim-rank">{{ $rankNumber }}</span>
        @endif
        <span class="phim-card-play"><i class="fa fa-play"></i> Xem ngay</span>
    </a>
    <h3><a href="{{ $movie->getUrl() }}">{{ $movie->name }}</a></h3>
    <div class="phim-card-meta">
        <span class="phim-accent">{{ $region ? $region->name : 'Đang cập nhật' }}</span>
        <i>•</i>
        <span>{{ $movie->publish_year }}</span>
        <span class="phim-views"><i class="fa fa-eye"></i> {{ number_format($movie->view_total) }}</span>
    </div>
    <div class="phim-badges">
        @if ($movie->episode_current)
            <span>{{ $movie->episode_current }}</span>
        @endif
        @if ($movie->language)
            @foreach (preg_split('/[,+]/', $movie->language) as $language)
                @if (trim($language))
                    <span class="{{ $loop->even ? 'is-blue' : 'is-light' }}">{{ trim($language) }}</span>
                @endif
            @endforeach
        @endif
    </div>
</li>
