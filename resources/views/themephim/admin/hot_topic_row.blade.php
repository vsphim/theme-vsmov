<div class="phim-topic-builder-row">
    <div class="phim-topic-builder-head">
        <span class="phim-topic-builder-index">{{ $rowNumber }}</span>
        <span class="phim-topic-builder-title">{{ $row[0] ?: 'Chủ đề mới' }}</span>
        <button class="btn btn-sm btn-light phim-topic-move-up" type="button" title="Đưa lên">
            <i class="la la-arrow-up"></i>
        </button>
        <button class="btn btn-sm btn-light phim-topic-move-down" type="button" title="Đưa xuống">
            <i class="la la-arrow-down"></i>
        </button>
        <button class="btn btn-sm btn-danger phim-topic-remove" type="button" title="Xóa">
            <i class="la la-trash"></i>
        </button>
    </div>
    <div class="phim-topic-builder-grid">
        <label>Tên chủ đề
            <input class="form-control" data-topic-column="0" type="text" value="{{ $row[0] }}" placeholder="Phim Hàn Quốc">
        </label>
        <label>Đường dẫn
            <input class="form-control" data-topic-column="1" type="text" value="{{ $row[1] }}" placeholder="/quoc-gia/han-quoc">
        </label>
        <label>Ảnh nền
            <input class="form-control" data-topic-column="2" type="text" value="{{ $row[2] }}" placeholder="https://...">
            <small>Để trống sẽ tự lấy poster phim đề cử.</small>
        </label>
    </div>
    <div class="phim-topic-builder-preview"
        @if ($row[2]) style="background-image:linear-gradient(90deg,rgba(18,20,31,.45),rgba(18,20,31,.12)),url('{{ $row[2] }}')" @endif>
        <span>{{ $row[0] ?: 'Chủ đề mới' }}</span>
    </div>
</div>
