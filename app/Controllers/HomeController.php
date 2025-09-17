<?php
// ホーム（LP）コントローラー

class HomeController
{
    public function index()
    {
        $db = Db::getInstance();

        // おすすめ実績を取得（最大6件）
        $featuredWorks = $db->fetchAll("
            SELECT w.*, c.name as category_name, c.slug as category_slug
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE w.is_published = 1 AND w.is_featured = 1
            ORDER BY w.sort_order ASC, w.created_at DESC
            LIMIT 6
        ");

        // 最新実績を取得（最大4件）
        $latestWorks = $db->fetchAll("
            SELECT w.*, c.name as category_name, c.slug as category_slug
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE w.is_published = 1
            ORDER BY w.created_at DESC
            LIMIT 4
        ");

        // カテゴリー一覧を取得
        $categories = $db->fetchAll("
            SELECT c.*, COUNT(w.id) as works_count
            FROM categories c
            LEFT JOIN works w ON c.id = w.category_id AND w.is_published = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC
        ");

        // SEO設定
        $seo = Seo::createForHome();

        // 構造化データ
        $schemas = [
            Schema::website(),
            Schema::organization(),
            Schema::localBusiness()
        ];

        if (!empty($featuredWorks)) {
            $schemas[] = Schema::worksList($featuredWorks);
        }

        $schema = implode("\n", $schemas);

        // ビューに渡すデータ
        $data = [
            'page' => 'home',
            'seo' => $seo,
            'schema' => $schema,
            'featuredWorks' => $featuredWorks,
            'latestWorks' => $latestWorks,
            'categories' => $categories,
            'bodyClass' => 'page-home'
        ];

        return $this->render($data);
    }

    private function render($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/layouts/base.php';
        return ob_get_clean();
    }
}