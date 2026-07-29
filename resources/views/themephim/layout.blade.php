@extends('themes::layout')

@php
    $menu = \VsMov\Core\Models\Menu::getTree();
@endphp

@push('header')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/themes/phim/css/font-awesome.css">
    <link rel="stylesheet" href="/themes/phim/css/owl.carousel.css">
    <link rel="stylesheet" href="/themes/phim/css/custom.css?v=1.0.14">
    <script src="/themes/phim/js/jquery.min.js"></script>
    <script src="/themes/phim/js/jquery.lazyload.min.js"></script>
    <script src="/themes/phim/js/js.cookie.js"></script>
    <script src="/themes/phim/js/movie-actions.js?v=1.0.1" defer></script>
    <script src="/themes/phim/js/hero-slider.js?v=1.0.0" defer></script>
@endpush

@section('body')
    <div id="phim-app">
        @include('themes::themephim.inc.header')

        <main class="phim-main">
            <div class="phim-container">
                @if (get_theme_option('ads_header'))
                    <div class="phim-ad">{!! get_theme_option('ads_header') !!}</div>
                @endif

                @yield('slider_recommended')
                @yield('breadcrumb')
                @yield('content')
            </div>
        </main>

        {!! get_theme_option('footer') !!}
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('img.lazy').lazyload({ effect: 'fadeIn' });

            $('.phim-nav-toggle').on('click', function () {
                $('.phim-nav').toggleClass('is-open');
                $(this).toggleClass('is-open');
            });

            $('.phim-search-toggle').on('click', function () {
                $('.phim-search').toggleClass('is-open');
                if ($('.phim-search').hasClass('is-open')) {
                    $('.phim-search input').trigger('focus');
                }
            });
        });
    </script>
@endpush

@section('footer')
    @if (get_theme_option('ads_catfish'))
        {!! get_theme_option('ads_catfish') !!}
    @endif

    <script src="/themes/phim/js/jquery.raty.js"></script>
    {!! setting('site_scripts_google_analytics') !!}
@endsection
