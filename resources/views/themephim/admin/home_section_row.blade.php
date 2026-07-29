<div class="phim-builder-row">
    <div class="phim-builder-row-head">
        <span class="phim-builder-index">{{ $rowNumber }}</span>
        <span class="phim-builder-title">{{ $row[0] ?: 'Section mới' }}</span>
        <button class="btn btn-sm btn-light phim-move-up" type="button" title="Đưa lên"><i class="la la-arrow-up"></i></button>
        <button class="btn btn-sm btn-light phim-move-down" type="button" title="Đưa xuống"><i class="la la-arrow-down"></i></button>
        <button class="btn btn-sm btn-danger phim-remove-section" type="button" title="Xóa"><i class="la la-trash"></i></button>
    </div>
    <div class="phim-builder-grid">
        <label>Tên section
            <input class="form-control" data-column="0" type="text" value="{{ $row[0] }}">
        </label>
        <label>Relation
            <input class="form-control" data-column="1" type="text" value="{{ $row[1] }}" placeholder="regions">
        </label>
        <label>Field
            <input class="form-control" data-column="2" type="text" value="{{ $row[2] }}" placeholder="slug">
        </label>
        <label>Giá trị
            <input class="form-control" data-column="3" type="text" value="{{ $row[3] }}" placeholder="han-quoc">
        </label>
        <label>Số phim
            <input class="form-control" data-column="4" type="number" min="1" max="50" value="{{ $row[4] ?: 8 }}">
        </label>
        <label>Link xem tất cả
            <input class="form-control" data-column="5" type="text" value="{{ $row[5] ?: '/' }}">
        </label>
    </div>
    <div class="phim-builder-options">
        <label>Kiểu hiển thị
            <select class="form-control phim-style-select" data-column="6">
                <option value="style1" @if ($row[6] === 'style1') selected @endif>Style 1 — Top xếp hạng, ảnh dọc</option>
                <option value="style2" @if ($row[6] === 'style2') selected @endif>Style 2 — Lưới ảnh ngang trong panel</option>
                <option value="style3" @if ($row[6] === 'style3') selected @endif>Style 3 — Banner phim nổi bật</option>
                <option value="style4" @if ($row[6] === 'style4') selected @endif>Style 4 — Hàng phim sắp chiếu</option>
            </select>
        </label>
        <label>Sắp xếp theo
            <select class="form-control" data-column="7">
                @foreach ([
                    'updated_at' => 'Mới cập nhật',
                    'created_at' => 'Mới đăng',
                    'publish_year' => 'Năm phát hành',
                    'view_total' => 'Tổng lượt xem',
                    'view_day' => 'Lượt xem hôm nay',
                    'view_week' => 'Lượt xem tuần',
                    'view_month' => 'Lượt xem tháng',
                    'name' => 'Tên phim',
                ] as $sortValue => $sortLabel)
                    <option value="{{ $sortValue }}" @if ($row[7] === $sortValue) selected @endif>{{ $sortLabel }}</option>
                @endforeach
            </select>
        </label>
        <label>Thứ tự
            <select class="form-control" data-column="8">
                <option value="desc" @if ($row[8] !== 'asc') selected @endif>Giảm dần</option>
                <option value="asc" @if ($row[8] === 'asc') selected @endif>Tăng dần</option>
            </select>
        </label>
    </div>
    <div class="phim-style-preview" data-style="{{ $row[6] }}">
        @for ($i = 1; $i <= 4; $i++)<span data-rank="{{ $i }}"></span>@endfor
    </div>
</div>
