@php
    $rawSections = old('latest', $field['value'] ?? $field['default'] ?? '');
    $sectionRows = collect(preg_split('/[\r\n]+/', (string) $rawSections))
        ->filter(function ($line) {
            return trim($line) !== '';
        })
        ->map(function ($line) {
            $columns = array_pad(explode('|', trim($line)), 9, '');
            $style = strtolower(trim($columns[6] ?: 'style2'));
            $style = preg_match('/^[1-4]$/', $style) ? 'style' . $style : str_replace(['-', '_', ' '], '', $style);
            $columns[6] = in_array($style, ['style1', 'style2', 'style3', 'style4'], true) ? $style : 'style2';
            $columns[7] = $columns[7] ?: 'updated_at';
            $columns[8] = strtolower($columns[8]) === 'asc' ? 'asc' : 'desc';
            return $columns;
        })
        ->values();
@endphp

<div id="phim-home-sections" class="phim-home-builder">
    <label class="phim-builder-label">Home Page</label>
    <p class="phim-builder-help">Mỗi section có dữ liệu và kiểu hiển thị riêng. Có thể kéo thứ tự bằng hai nút mũi tên.</p>

    <textarea name="latest" class="d-none phim-sections-value">{{ $rawSections }}</textarea>

    <div class="phim-builder-rows">
        @foreach ($sectionRows as $row)
            @include('themes::themephim.admin.home_section_row', ['row' => $row, 'rowNumber' => $loop->iteration])
        @endforeach
    </div>

    <button class="btn btn-primary phim-add-section" type="button">
        <i class="la la-plus"></i> Thêm section
    </button>
</div>

<template id="phim-home-section-template">
    @include('themes::themephim.admin.home_section_row', [
        'row' => ['Phim mới', '', 'type', 'series', '8', '/', 'style2', 'updated_at', 'desc'],
        'rowNumber' => '__ROW__',
    ])
</template>

@push('crud_fields_styles')
    <style>
        .phim-home-builder{width:100%}.phim-builder-label{display:block;font-size:15px;font-weight:700;color:#1f2d4d}
        .phim-builder-help{margin:-2px 0 16px;color:#6c757d}.phim-builder-row{margin-bottom:16px;border:1px solid #dfe3ea;border-radius:7px;background:#fff;overflow:hidden}
        .phim-builder-row-head{display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f4f6f9;border-bottom:1px solid #e3e6eb}
        .phim-builder-index{width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;color:#fff;background:#f58a1f;border-radius:50%;font-weight:700}
        .phim-builder-title{margin-right:auto;font-weight:700;color:#1f2d4d}.phim-builder-row-head .btn{padding:3px 7px}
        .phim-builder-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1.25fr .65fr 1.6fr;gap:12px;padding:14px}
        .phim-builder-grid label,.phim-builder-options label{margin:0;color:#526078;font-size:12px}
        .phim-builder-grid input,.phim-builder-options select{height:38px;margin-top:5px;font-size:13px}
        .phim-builder-options{display:grid;grid-template-columns:2fr 1.2fr 1fr;gap:12px;padding:0 14px 14px}
        .phim-style-select{font-weight:600}.phim-style-preview{margin:0 14px 14px;padding:11px 12px;display:grid;grid-template-columns:repeat(4,1fr);gap:8px;background:#13151d;border-radius:6px}
        .phim-style-preview span{height:26px;border-radius:3px;background:#2a2d3d}.phim-style-preview[data-style=style1] span{height:45px;background:linear-gradient(150deg,#445,#171820)}
        .phim-style-preview[data-style=style1] span:before{content:attr(data-rank);color:#fff;font-size:22px;font-weight:800}
        .phim-style-preview[data-style=style2]{background:#272a3a}.phim-style-preview[data-style=style3]{grid-template-columns:2fr repeat(3,.55fr);background:linear-gradient(90deg,#201a18,#685447)}
        .phim-style-preview[data-style=style4] span{height:30px;border-bottom:3px solid #444}
        @media(max-width:1100px){.phim-builder-grid{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:700px){.phim-builder-grid,.phim-builder-options{grid-template-columns:1fr}.phim-builder-row-head{flex-wrap:wrap}}
    </style>
@endpush

@push('crud_fields_scripts')
    <script>
        (function () {
            const builder = document.getElementById('phim-home-sections');
            if (!builder || builder.dataset.ready) return;
            builder.dataset.ready = '1';

            const rows = builder.querySelector('.phim-builder-rows');
            const valueField = builder.querySelector('.phim-sections-value');
            const template = document.getElementById('phim-home-section-template');

            function clean(value) {
                return String(value || '').replace(/[|\r\n]+/g, ' ').trim();
            }

            function refresh() {
                const serialized = [];
                rows.querySelectorAll('.phim-builder-row').forEach((row, index) => {
                    row.querySelector('.phim-builder-index').textContent = index + 1;
                    row.querySelector('.phim-builder-title').textContent =
                        row.querySelector('[data-column="0"]').value || 'Section mới';
                    const style = row.querySelector('[data-column="6"]').value;
                    row.querySelector('.phim-style-preview').dataset.style = style;
                    serialized.push(Array.from(row.querySelectorAll('[data-column]'))
                        .sort((a, b) => Number(a.dataset.column) - Number(b.dataset.column))
                        .map(input => clean(input.value))
                        .join('|'));
                });
                valueField.value = serialized.join('\n');
            }

            builder.addEventListener('input', refresh);
            builder.addEventListener('change', refresh);
            builder.addEventListener('click', function (event) {
                const row = event.target.closest('.phim-builder-row');
                if (event.target.closest('.phim-remove-section') && row) row.remove();
                if (event.target.closest('.phim-move-up') && row && row.previousElementSibling) {
                    rows.insertBefore(row, row.previousElementSibling);
                }
                if (event.target.closest('.phim-move-down') && row && row.nextElementSibling) {
                    rows.insertBefore(row.nextElementSibling, row);
                }
                refresh();
            });

            builder.querySelector('.phim-add-section').addEventListener('click', function () {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = template.innerHTML.replace(/__ROW__/g, String(rows.children.length + 1)).trim();
                rows.appendChild(wrapper.firstElementChild);
                refresh();
            });

            builder.closest('form').addEventListener('submit', refresh);
            refresh();
        })();
    </script>
@endpush
