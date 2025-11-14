<?php

class HomeController extends Controller
{
    public function index()
    {
        try {
            // データベースから実績を取得
            $db = Database::getInstance();

            // おすすめ実績を取得
            $featuredWorks = $db->fetchAll("
                SELECT w.*, c.name as category_name
                FROM works w
                LEFT JOIN categories c ON w.category_id = c.id
                WHERE w.is_published = 1
                ORDER BY w.created_at DESC
                LIMIT 6
            ");

            // データベースにデータがない場合はダミーデータを使用
            if (empty($featuredWorks)) {
                $featuredWorks = [
                    [
                        'slug' => 'modern-garden-1',
                        'title' => 'モダン住宅の外構植栽',
                        'description' => '砂利とシンボルツリーを配置したシンプルでモダンな外構デザイン。お手入れしやすく美しい空間を実現しました。',
                        'main_image' => 'assets/img/works/1.jpg',
                        'category_name' => '住宅外構',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'japanese-garden',
                        'title' => '和風庭園の植栽',
                        'description' => '赤いモミジと庭石を配置した趣のある和風庭園。四季折々の美しさを楽しめる設計です。',
                        'main_image' => 'assets/img/works/3.jpg',
                        'category_name' => '和風庭園',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'lawn-installation',
                        'title' => '人工芝の施工',
                        'description' => '美しい緑の芝生を施工。一年中青々とした美しい庭を維持できます。',
                        'main_image' => 'assets/img/works/17.jpg',
                        'category_name' => '芝生施工',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'stone-wall-garden',
                        'title' => '石壁と植栽の調和',
                        'description' => 'モダンな石壁に低木とグリーンを配置。メンテナンスしやすく美しい外構です。',
                        'main_image' => 'assets/img/works/10.jpg',
                        'category_name' => '外構デザイン',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'entrance-planting',
                        'title' => '玄関周りの植栽デザイン',
                        'description' => '玄関を彩る植栽デザイン。来客を温かく迎える緑の空間を演出しました。',
                        'main_image' => 'assets/img/works/22.jpg',
                        'category_name' => '玄関植栽',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ],
                    [
                        'slug' => 'fence-planting',
                        'title' => 'フェンス沿いの植栽',
                        'description' => 'フェンス沿いにシンボルツリーと低木を配置。プライバシーを守りながら緑豊かな空間に。',
                        'main_image' => 'assets/img/works/11.jpg',
                        'category_name' => 'フェンス植栽',
                        'location' => '伊勢市',
                        'created_at' => date('Y-m-d')
                    ]
                ];
            }

            // home.phpビューを使用
            return $this->view('layouts.base', [
                'title' => '小久保植樹園 | 伊勢市の植樹・造園専門業者',
                'content' => $this->view('home', [
                    'featuredWorks' => $featuredWorks
                ])
            ]);

        } catch (Exception $e) {
            return '<h1>Home Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
    }
}
