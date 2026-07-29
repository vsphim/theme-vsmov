@php
    $rawTopics = old('hot_topics', $field['value'] ?? $field['default'] ?? '');
    $topicRows = collect(preg_split('/[\r\n]+/', (string) $rawTopics))
        ->filter(function ($line) {
            return trim($line) !== '';
        })
        ->map(function ($line) {
            return array_pad(explode('|', trim($line)), 3, '');
        })
        ->values();
@endphp

<div id="phim-hot-topics-builder" class="phim-topic-builder">
    <label class="phim-topic-builder-label">Các ô chủ đề</label>
    <p class="phim-topic-builder-help">Chỉnh tên, liên kết và ảnh nền cho từng ô. Tối đa 8 chủ đề.</p>

    <textarea name="hot_topics" class="d-none phim-topic-builder-value">{{ $rawTopics }}</textarea>

    <div class="phim-topic-builder-rows">
        @foreach ($topicRows as $row)
            @include('themes::themephim.admin.hot_topic_row', ['row' => $row, 'rowNumber' => $loop->iteration])
        @endforeach
    </div>

    <button class="btn btn-primary phim-topic-add" type="button">
        <i class="la la-plus"></i> Thêm chủ đề
    </button>
</div>

<template id="phim-hot-topic-template">
    @include('themes::themephim.admin.hot_topic_row', [
        'row' => ['Chủ đề mới', '#', ''],
        'rowNumber' => '__TOPIC_ROW__',
    ])
</template>

@push('crud_fields_styles')
    <style>
        .phim-topic-builder{width:100%;margin-bottom:24px}.phim-topic-builder-label{display:block;font-size:15px;font-weight:700;color:#1f2d4d}
        .phim-topic-builder-help{margin:-2px 0 16px;color:#6c757d}.phim-topic-builder-row{margin-bottom:14px;border:1px solid #dfe3ea;border-radius:7px;background:#fff;overflow:hidden}
        .phim-topic-builder-head{display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f4f6f9;border-bottom:1px solid #e3e6eb}
        .phim-topic-builder-index{width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;color:#fff;background:#f58a1f;border-radius:50%;font-weight:700}
        .phim-topic-builder-title{margin-right:auto;font-weight:700;color:#1f2d4d}.phim-topic-builder-head .btn{padding:3px 7px}
        .phim-topic-builder-grid{display:grid;grid-template-columns:1fr 1.25fr 2fr;gap:12px;padding:14px}
        .phim-topic-builder-grid label{margin:0;color:#526078;font-size:12px}.phim-topic-builder-grid input{height:38px;margin-top:5px;font-size:13px}
        .phim-topic-builder-grid small{display:block;margin-top:4px;color:#8a93a3}.phim-topic-builder-preview{height:72px;margin:0 14px 14px;padding:16px 20px;display:flex;align-items:center;color:#fff;background-color:#414352;background-position:center;background-size:cover;border-radius:6px;font-size:18px;font-weight:700;text-shadow:0 2px 8px rgba(0,0,0,.8)}
        @media(max-width:800px){.phim-topic-builder-grid{grid-template-columns:1fr}}
    </style>
@endpush

@push('crud_fields_scripts')
    <script>
        (function () {
            var builder = document.getElementById('phim-hot-topics-builder');
            if (!builder || builder.dataset.ready) return;
            builder.dataset.ready = '1';

            var rows = builder.querySelector('.phim-topic-builder-rows');
            var valueField = builder.querySelector('.phim-topic-builder-value');
            var template = document.getElementById('phim-hot-topic-template');

            function clean(value) {
                return String(value || '').replace(/[|\r\n]+/g, ' ').trim();
            }

            function updatePreview(row) {
                var name = row.querySelector('[data-topic-column="0"]').value || 'Chủ đề mới';
                var image = row.querySelector('[data-topic-column="2"]').value.trim();
                var preview = row.querySelector('.phim-topic-builder-preview');

                row.querySelector('.phim-topic-builder-title').textContent = name;
                preview.querySelector('span').textContent = name;
                preview.style.backgroundImage = image
                    ? 'linear-gradient(90deg,rgba(18,20,31,.45),rgba(18,20,31,.12)),url("' + image.replace(/["\\]/g, '\\$&') + '")'
                    : '';
            }

            function refresh() {
                var serialized = [];

                rows.querySelectorAll('.phim-topic-builder-row').forEach(function (row, index) {
                    row.querySelector('.phim-topic-builder-index').textContent = index + 1;
                    updatePreview(row);
                    serialized.push(Array.from(row.querySelectorAll('[data-topic-column]'))
                        .sort(function (a, b) {
                            return Number(a.dataset.topicColumn) - Number(b.dataset.topicColumn);
                        })
                        .map(function (input) {
                            return clean(input.value);
                        })
                        .join('|'));
                });

                valueField.value = serialized.join('\n');
                builder.querySelector('.phim-topic-add').disabled = rows.children.length >= 8;
            }

            builder.addEventListener('input', refresh);
            builder.addEventListener('click', function (event) {
                var row = event.target.closest('.phim-topic-builder-row');

                if (event.target.closest('.phim-topic-remove') && row) row.remove();
                if (event.target.closest('.phim-topic-move-up') && row && row.previousElementSibling) {
                    rows.insertBefore(row, row.previousElementSibling);
                }
                if (event.target.closest('.phim-topic-move-down') && row && row.nextElementSibling) {
                    rows.insertBefore(row.nextElementSibling, row);
                }
                refresh();
            });

            builder.querySelector('.phim-topic-add').addEventListener('click', function () {
                if (rows.children.length >= 8) return;

                var wrapper = document.createElement('div');
                wrapper.innerHTML = template.innerHTML
                    .replace(/__TOPIC_ROW__/g, String(rows.children.length + 1))
                    .trim();
                rows.appendChild(wrapper.firstElementChild);
                refresh();
            });

            var form = builder.closest('form');
            if (form) form.addEventListener('submit', refresh);
            refresh();
        })();
    </script>
@endpush
