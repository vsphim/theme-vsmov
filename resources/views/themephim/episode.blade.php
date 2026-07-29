@extends('themes::themephim.layout')

@php
    $episodeLabel = \Illuminate\Support\Str::startsWith(\Illuminate\Support\Str::lower($episode->name), 'tập')
        ? $episode->name
        : 'Tập ' . $episode->name;
    $region = $currentMovie->regions->first();
    $personPlaceholder = '/themes/phim/images/person-placeholder.svg';
@endphp

@push('header')
@endpush

@section('breadcrumb')
    <ol class="phim-breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="/" itemprop="item">
                <i class="fa fa-home"></i>
                <span itemprop="name">Trang chủ</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        @if ($region)
            <li><i class="fa fa-angle-right"></i></li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a href="{{ $region->getUrl() }}" itemprop="item">
                    <span itemprop="name">{{ $region->name }}</span>
                </a>
                <meta itemprop="position" content="2" />
            </li>
        @endif
        <li><i class="fa fa-angle-right"></i></li>
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="{{ $currentMovie->getUrl() }}" itemprop="item">
                <span itemprop="name">{{ $currentMovie->name }}</span>
            </a>
            <meta itemprop="position" content="3" />
        </li>
        <li><i class="fa fa-angle-right"></i></li>
        <li class="is-active">Xem phim {{ $currentMovie->name }}</li>
    </ol>
@endsection

@section('content')
    <article class="phim-watch-page" id="player-video">
        <h1 class="phim-watch-title">{{ $currentMovie->name }}</h1>

        <div class="phim-watch-stage">
            <div class="box-player phim-watch-player" id="box-player">
                <div id="player" class="embed-responsive embed-responsive-16by9"></div>
                <div class="loading-container">
                    <div class="loading-player"></div>
                </div>
            </div>

            <div class="phim-watch-toolbar">
                <div class="phim-watch-server-list" aria-label="Danh sách server">
                    @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                        <button type="button" data-id="{{ $server->id }}" data-link="{{ $server->link }}"
                            data-type="{{ $server->type }}" onclick="chooseStreamingServer(this)"
                            class="streaming-server btn-sv btn-hls" aria-pressed="false">
                            Server {{ $loop->iteration }}
                        </button>
                    @endforeach
                </div>

                <div class="phim-watch-meta">
                    <span>IMDb {{ $currentMovie->getRatingStar() }}</span>
                    <span class="is-yellow">HD</span>
                    @if ($currentMovie->publish_year)<span>{{ $currentMovie->publish_year }}</span>@endif
                    @if ($currentMovie->episode_time)<span>{{ $currentMovie->episode_time }}</span>@endif
                    @if ($currentMovie->episode_current)<span>{{ $currentMovie->episode_current }}</span>@endif
                    @if ($currentMovie->language)<span class="is-light">{{ $currentMovie->language }}</span>@endif
                </div>

                <div class="phim-watch-actions">
                    <button type="button" class="phim-watch-action video-btn" id="btn_lightbulb"
                        aria-label="Tắt đèn" title="Tắt đèn">
                        <i class="fa fa-lightbulb-o"></i>
                    </button>
                    <button type="button" class="phim-watch-action phim-favourite-button"
                        data-phim-favourite data-movie-id="{{ $currentMovie->id }}"
                        data-movie-name="{{ $currentMovie->name }}"
                        data-movie-url="{{ $currentMovie->getUrl() }}"
                        data-movie-poster="{{ $currentMovie->getPosterUrl() }}"
                        aria-label="Thêm {{ $currentMovie->name }} vào yêu thích"
                        aria-pressed="false" title="Thêm vào yêu thích">
                        <i class="fa fa-heart"></i>
                    </button>
                    <button type="button" class="phim-watch-action phim-share-button"
                        data-phim-share data-share-url="{{ url()->current() }}"
                        aria-label="Sao chép liên kết tập phim" title="Sao chép liên kết">
                        <i class="fa fa-share"></i>
                    </button>
                </div>
            </div>

            <p class="phim-watch-description">
                {{ \Illuminate\Support\Str::limit(strip_tags($currentMovie->content), 260) }}
            </p>

            @if ($currentMovie->type === 'series' && $currentMovie->episodes->count())
                <div class="phim-watch-episodes">
                    @foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $serverName => $data)
                        <div class="phim-watch-episode-row">
                            <strong><i class="fa fa-film"></i> {{ $serverName }}</strong>
                            <div class="list-episode">
                                @foreach ($data->sortByDesc('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                                    @php
                                        $episodeName = \Illuminate\Support\Str::startsWith(\Illuminate\Support\Str::lower($name), 'tập')
                                            ? $name
                                            : 'Tập ' . $name;
                                    @endphp
                                    <a href="{{ $item->sortByDesc('type')->first()->getUrl() }}"
                                        class="@if ($item->contains($episode)) current @endif"
                                        title="{{ $episodeName }}">{{ $episodeName }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="phim-watch-lower">
            <div class="phim-watch-lower-main">
                @if ($movie_related->count())
                    <section class="phim-watch-related">
                        <div class="phim-section-heading is-centered">
                            <h2>Đề xuất phim liên quan</h2>
                        </div>
                        <ul class="phim-movie-grid is-three-columns">
                            @foreach ($movie_related->where('id', '!=', $currentMovie->id)->take(10) as $movie)
                                @php $xClass = 'phim-card'; @endphp
                                @include('themes::themephim.inc.sections_movies_item')
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside class="phim-people phim-watch-people">
                <h2>Đạo diễn</h2>
                <div class="phim-people-grid">
                    @forelse ($currentMovie->directors->take(3) as $director)
                        <a href="{{ $director->getUrl() }}">
                            <span>
                                <img src="{{ $director->thumb_url ?: $personPlaceholder }}"
                                    onerror="this.onerror=null;this.src='{{ $personPlaceholder }}';"
                                    loading="lazy" alt="{{ $director->name }}">
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
                                    loading="lazy" alt="{{ $actor->name }}">
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
                    <dt>Từ khóa</dt>
                    <dd>
                        @foreach ($currentMovie->tags->take(6) as $tag)
                            <a href="{{ $tag->getUrl() }}">{{ $tag->name }}</a>{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </dd>
                </dl>
            </aside>
        </div>
    </article>
@endsection

@push('scripts')
    <script src="/themes/phim/player/js/p2p-media-loader-core.min.js"></script>
    <script src="/themes/phim/player/js/p2p-media-loader-hlsjs.min.js"></script>

    <script src="/js/jwplayer-8.9.3.js"></script>
    <script src="/js/hls.min.js"></script>
    <script src="/js/jwplayer.hlsjs.min.js"></script>

    <script>
        var episode_id = {{ $episode->id }};
        const wrapper = document.getElementById('player');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;


            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('btn-success');
                server.setAttribute('aria-pressed', 'false');
            })
            el.classList.add('btn-success');
            el.setAttribute('aria-pressed', 'true');

            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            $('.loadingData').hide();
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/phim/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="350px" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="350px" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="350px" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe width="100%" height="350px" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    image: "{{ $currentMovie->getPosterUrl() }}",
                    file: link,
                    playbackRateControls: true,
                    playbackRates: [0.25, 0.75, 1, 1.25],
                    sharing: {
                        sites: [
                            "reddit",
                            "facebook",
                            "twitter",
                            "googleplus",
                            "email",
                            "linkedin",
                        ],
                    },
                    volume: 100,
                    mute: false,
                    autostart: true,
                    logo: {
                        file: "{{ Setting::get('jwplayer_logo_file') }}",
                        link: "{{ Setting::get('jwplayer_logo_link') }}",
                        position: "{{ Setting::get('jwplayer_logo_position') }}",
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua"
                    }
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                        var engine = new p2pml.hlsjs.Engine(engine_config);
                        player.setup(objSetup);
                        jwplayer_hls_provider.attach();
                        p2pml.hlsjs.initJwPlayer(player, {
                            liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                            maxBufferLength: 300,
                            loader: engine.createLoaderClass(),
                        });
                    } else {
                        player.setup(objSetup);
                    }
                } else {
                    player.setup(objSetup);
                }


                const resumeData = 'OPCMS-PlayerPosition-' + id;
                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{ $episode->id }}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lightButton = document.getElementById('btn_lightbulb');

            if (!lightButton) {
                return;
            }

            const setLightsOff = function(isOff) {
                document.body.classList.toggle('phim-lights-off', isOff);
                lightButton.classList.toggle('off', isOff);
                lightButton.setAttribute('aria-pressed', isOff ? 'true' : 'false');
                lightButton.setAttribute('aria-label', isOff ? 'Bật đèn' : 'Tắt đèn');
                lightButton.setAttribute('title', isOff ? 'Bật đèn' : 'Tắt đèn');

                let overlay = document.getElementById('background_lamp');

                if (isOff && !overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'background_lamp';
                    overlay.setAttribute('aria-hidden', 'true');
                    overlay.addEventListener('click', function() {
                        setLightsOff(false);
                    });
                    document.body.appendChild(overlay);
                } else if (!isOff && overlay) {
                    overlay.remove();
                }
            };

            lightButton.addEventListener('click', function() {
                setLightsOff(!document.body.classList.contains('phim-lights-off'));
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && document.body.classList.contains('phim-lights-off')) {
                    setLightsOff(false);
                }
            });
        });
    </script>

    <script type="text/javascript">
        const URL_POST_RATING = '{{ route('movie.rating', ['movie' => $currentMovie->slug]) }}';
    </script>
    <script type="text/javascript" src="/themes/phim/js/filmdetail.js?v=1.2.2"></script>

@endpush
