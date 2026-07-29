@php
    $sectionStyle = in_array($item['style'] ?? 'style2', ['style1', 'style2', 'style3', 'style4'], true)
        ? $item['style']
        : 'style2';
@endphp

@if ($item['data']->count())
    @include('themes::themephim.inc.home.' . $sectionStyle)
@endif
