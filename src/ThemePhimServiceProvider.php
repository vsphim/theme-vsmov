<?php

namespace VsMov\ThemePhim;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ThemePhimServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->setupDefaultThemeCustomizer();
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'themes');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('themes/phim')
        ], 'phim-assets');
    }

    protected function setupDefaultThemeCustomizer()
    {
        config(['themes' => array_merge(config('themes', []), [
            'phim' => [
                'name' => 'Phim',
                'author' => 'vsmov@gmail.com',
                'package_name' => 'vsmov/theme-phim',
                'publishes' => ['phim-assets'],
                'preview_image' => '/themes/phim/screenshot.png',
                'options' => [
                    [
                        'name' => 'recommendations_limit',
                        'label' => 'Recommended movies limit',
                        'type' => 'number',
                        'value' => 10,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'per_page_limit',
                        'label' => 'Pages limit',
                        'type' => 'number',
                        'value' => 20,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'movie_related_limit',
                        'label' => 'Movies related limit',
                        'type' => 'number',
                        'value' => 10,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'hot_topics_title',
                        'label' => 'Tiêu đề chủ đề',
                        'type' => 'text',
                        'value' => 'Chủ đề hot',
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-12',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'hot_topics',
                        'label' => 'Chủ đề hot',
                        'type' => 'view',
                        'view' => 'themes::themephim.admin.hot_topics',
                        'value' => <<<EOT
                        Trang chủ|/|
                        Thể loại|#|
                        Quốc gia|#|
                        EOT,
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'latest',
                        'label' => 'Home Page',
                        'type' => 'view',
                        'view' => 'themes::themephim.admin.home_sections',
                        'value' => <<<EOT
                        Top 10 hôm nay||||10|/|style1|view_day|desc
                        Phim Hàn Quốc mới|regions|slug|han-quoc|4|/quoc-gia/han-quoc|style2|updated_at|desc
                        Phim Việt Nam mới|regions|slug|viet-nam|5|/quoc-gia/viet-nam|style3|updated_at|desc
                        Phim sắp chiếu||status|trailer|4|/danh-sach/phim-sap-chieu|style4|publish_year|desc
                        EOT,
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'hotest',
                        'label' => 'Danh sách hot',
                        'type' => 'code',
                        'hint' => 'Label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_template (top_text|top_thumb)',
                        'value' => <<<EOT
                        Sắp chiếu||status|trailer|publish_year|desc|10|top_text
                        Top phim lẻ||type|single|view_week|desc|10|top_thumb
                        Top phim bộ||type|series|view_week|desc|10|top_thumb
                        EOT,
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'additional_css',
                        'label' => 'Additional CSS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'body_attributes',
                        'label' => 'Body attributes',
                        'type' => 'text',
                        'value' => "",
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'additional_header_js',
                        'label' => 'Header JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_body_js',
                        'label' => 'Body JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_footer_js',
                        'label' => 'Footer JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'footer',
                        'label' => 'Footer',
                        'type' => 'code',
                        'value' => <<<EOT
                        <footer class="phim-footer">
                            <div class="phim-container phim-footer-inner">
                                <div class="phim-footer-brand">
                                    <a class="phim-logo" href="/"><span>VS</span><b>M</b><span>OV</span></a>
                                    <p>Trang xem phim online chất lượng cao miễn phí, cập nhật phim mới mỗi ngày với phụ đề,
                                        thuyết minh và lồng tiếng.</p>
                                </div>
                                <div>
                                    <h3>Khám phá</h3>
                                    <a href="/">Trang chủ</a>
                                    <a href="/danh-sach/phim-le">Phim lẻ</a>
                                    <a href="/danh-sach/phim-bo">Phim bộ</a>
                                </div>
                                <div>
                                    <h3>Thông tin</h3>
                                    <a href="#">Điều khoản</a>
                                    <a href="#">Chính sách riêng tư</a>
                                    <a href="#">Liên hệ</a>
                                </div>
                                <div class="phim-footer-social">
                                    <h3>Theo dõi</h3>
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-youtube-play"></i></a>
                                    <a href="#"><i class="fa fa-telegram"></i></a>
                                </div>
                            </div>
                        </footer>
                        EOT,
                        'tab' => 'Custom HTML'
                    ],
                    [
                        'name' => 'ads_header',
                        'label' => 'Ads header',
                        'type' => 'code',
                        'value' => '',
                        'tab' => 'Ads'
                    ],
                    [
                        'name' => 'ads_catfish',
                        'label' => 'Ads catfish',
                        'type' => 'code',
                        'value' => '',
                        'tab' => 'Ads'
                    ]
                ],
            ]
        ])]);
    }
}
