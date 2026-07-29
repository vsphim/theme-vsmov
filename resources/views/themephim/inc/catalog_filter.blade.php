<form id="form-filter" class="phim-filter" method="GET" action="/">
    <label>
        <span>Sắp xếp</span>
        <select name="filter[sort]">
            <option value="">Mặc định</option>
            <option value="update" @if (data_get(request('filter'), 'sort') === 'update') selected @endif>Mới cập nhật</option>
            <option value="create" @if (data_get(request('filter'), 'sort') === 'create') selected @endif>Mới đăng</option>
            <option value="year" @if (data_get(request('filter'), 'sort') === 'year') selected @endif>Năm sản xuất</option>
            <option value="view" @if (data_get(request('filter'), 'sort') === 'view') selected @endif>Lượt xem</option>
        </select>
    </label>
    <label>
        <span>Định dạng</span>
        <select name="filter[type]">
            <option value="">Tất cả</option>
            <option value="series" @if (data_get(request('filter'), 'type') === 'series') selected @endif>Phim bộ</option>
            <option value="single" @if (data_get(request('filter'), 'type') === 'single') selected @endif>Phim lẻ</option>
        </select>
    </label>
    <label>
        <span>Thể loại</span>
        <select name="filter[category]">
            <option value="">Tất cả</option>
            @foreach (\VsMov\Core\Models\Category::fromCache()->all() as $filterCategory)
                <option value="{{ $filterCategory->id }}"
                    @if ((string) data_get(request('filter'), 'category') === (string) $filterCategory->id || (isset($category) && $category->id === $filterCategory->id)) selected @endif>
                    {{ $filterCategory->name }}
                </option>
            @endforeach
        </select>
    </label>
    <label>
        <span>Quốc gia</span>
        <select name="filter[region]">
            <option value="">Tất cả</option>
            @foreach (\VsMov\Core\Models\Region::fromCache()->all() as $filterRegion)
                <option value="{{ $filterRegion->id }}"
                    @if ((string) data_get(request('filter'), 'region') === (string) $filterRegion->id || (isset($region) && $region->id === $filterRegion->id)) selected @endif>
                    {{ $filterRegion->name }}
                </option>
            @endforeach
        </select>
    </label>
    <label>
        <span>Năm</span>
        <select name="filter[year]">
            <option value="">Tất cả</option>
            @foreach ($years as $year)
                <option value="{{ $year }}" @if ((string) data_get(request('filter'), 'year') === (string) $year) selected @endif>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </label>
    <button class="phim-btn phim-btn-primary" type="submit"><i class="fa fa-filter"></i> Lọc phim</button>
</form>
