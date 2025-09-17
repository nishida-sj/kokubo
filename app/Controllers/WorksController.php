<?php
// 施工実績コントローラー

class WorksController
{
    public function index()
    {
        $db = Db::getInstance();

        // リクエストパラメータ取得
        $categorySlug = $_GET['category'] ?? '';
        $tagSlug = $_GET['tag'] ?? '';
        $searchQuery = $_GET['q'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));

        // SQLクエリ構築
        $whereConditions = ['w.is_published = 1'];
        $params = [];

        // カテゴリーフィルター
        $selectedCategory = null;
        if (!empty($categorySlug)) {
            $selectedCategory = $db->fetch("SELECT * FROM categories WHERE slug = :slug", ['slug' => $categorySlug]);
            if ($selectedCategory) {
                $whereConditions[] = 'w.category_id = :category_id';
                $params['category_id'] = $selectedCategory['id'];
            }
        }

        // タグフィルター
        $selectedTag = null;
        if (!empty($tagSlug)) {
            $selectedTag = $db->fetch("SELECT * FROM tags WHERE slug = :slug", ['slug' => $tagSlug]);
            if ($selectedTag) {
                $whereConditions[] = 'EXISTS (SELECT 1 FROM work_tags wt WHERE wt.work_id = w.id AND wt.tag_id = :tag_id)';
                $params['tag_id'] = $selectedTag['id'];
            }
        }

        // 検索クエリ
        if (!empty($searchQuery)) {
            $whereConditions[] = '(w.title LIKE :search OR w.description LIKE :search OR w.location LIKE :search)';
            $params['search'] = '%' . $searchQuery . '%';
        }

        $whereClause = implode(' AND ', $whereConditions);

        // 実績データを取得（ページング）
        $sql = "
            SELECT w.*, c.name as category_name, c.slug as category_slug
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE {$whereClause}
            ORDER BY w.is_featured DESC, w.sort_order ASC, w.created_at DESC
        ";

        $pagination = $db->getPagination($sql, $params, $page, WORKS_PER_PAGE);

        // カテゴリー一覧を取得
        $categories = $db->fetchAll("
            SELECT c.*, COUNT(w.id) as works_count
            FROM categories c
            LEFT JOIN works w ON c.id = w.category_id AND w.is_published = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC
        ");

        // タグ一覧を取得
        $tags = $db->fetchAll("
            SELECT t.*, COUNT(wt.work_id) as works_count
            FROM tags t
            LEFT JOIN work_tags wt ON t.id = wt.tag_id
            LEFT JOIN works w ON wt.work_id = w.id AND w.is_published = 1
            GROUP BY t.id
            HAVING works_count > 0
            ORDER BY works_count DESC, t.name ASC
        ");

        // SEO設定
        $title = '施工実績';
        $description = '小久保工務店の施工実績をご紹介します。新築・リフォーム・増改築の豊富な事例をご確認ください。';

        if ($selectedCategory) {
            $title = $selectedCategory['name'] . 'の実績';
            $description = $selectedCategory['name'] . 'の施工実績をご紹介します。小久保工務店の豊富な経験と技術力をご確認ください。';
        }

        if ($selectedTag) {
            $title = $selectedTag['name'] . 'の実績';
            $description = $selectedTag['name'] . 'の施工実績をご紹介します。';
        }

        if (!empty($searchQuery)) {
            $title = '"' . $searchQuery . '"の検索結果';
            $description = '"' . $searchQuery . '"に関する施工実績の検索結果です。';
        }

        if ($page > 1) {
            $title .= ' - ' . $page . 'ページ目';
        }

        $seo = new Seo();
        $seo->setTitle($title)
            ->setDescription($description);

        // カノニカルURL設定
        $canonicalParams = [];
        if ($selectedCategory) $canonicalParams['category'] = $categorySlug;
        if ($selectedTag) $canonicalParams['tag'] = $tagSlug;
        if (!empty($searchQuery)) $canonicalParams['q'] = $searchQuery;
        if ($page > 1) $canonicalParams['page'] = $page;

        $canonicalUrl = 'works';
        if (!empty($canonicalParams)) {
            $canonicalUrl .= '?' . http_build_query($canonicalParams);
        }
        $seo->setCanonical($canonicalUrl);

        // 構造化データ
        $schemas = [];
        if (!empty($pagination['data'])) {
            $schemas[] = Schema::worksList($pagination['data'], $selectedCategory);
        }

        // パンくずリスト
        $breadcrumbs = [
            ['name' => 'ホーム', 'url' => ''],
            ['name' => '施工実績']
        ];

        if ($selectedCategory) {
            $breadcrumbs[1]['url'] = 'works';
            $breadcrumbs[] = ['name' => $selectedCategory['name']];
        }

        if (!empty($breadcrumbs)) {
            $schemas[] = Schema::breadcrumb($breadcrumbs);
        }

        $schema = implode("\n", $schemas);

        // ビューに渡すデータ
        $data = [
            'page' => 'works/index',
            'seo' => $seo,
            'schema' => $schema,
            'works' => $pagination['data'],
            'pagination' => $pagination,
            'categories' => $categories,
            'tags' => $tags,
            'selectedCategory' => $selectedCategory,
            'selectedTag' => $selectedTag,
            'searchQuery' => $searchQuery,
            'currentPage' => $page,
            'bodyClass' => 'page-works page-works-index'
        ];

        return $this->render($data);
    }

    public function show($slug)
    {
        $db = Db::getInstance();

        // 実績詳細を取得
        $work = $db->fetch("
            SELECT w.*, c.name as category_name, c.slug as category_slug
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE w.slug = :slug AND w.is_published = 1
        ", ['slug' => $slug]);

        if (!$work) {
            return $this->render404();
        }

        // 実績画像を取得
        $images = $db->fetchAll("
            SELECT * FROM work_images
            WHERE work_id = :work_id
            ORDER BY sort_order ASC, id ASC
        ", ['work_id' => $work['id']]);

        // 実績に関連するタグを取得
        $workTags = $db->fetchAll("
            SELECT t.* FROM tags t
            INNER JOIN work_tags wt ON t.id = wt.tag_id
            WHERE wt.work_id = :work_id
            ORDER BY t.name ASC
        ", ['work_id' => $work['id']]);

        // 関連実績を取得（同じカテゴリー、最大4件）
        $relatedWorks = $db->fetchAll("
            SELECT w.*, c.name as category_name, c.slug as category_slug
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE w.category_id = :category_id
            AND w.id != :current_id
            AND w.is_published = 1
            ORDER BY w.is_featured DESC, w.created_at DESC
            LIMIT 4
        ", [
            'category_id' => $work['category_id'],
            'current_id' => $work['id']
        ]);

        // 前後の実績を取得
        $prevWork = $db->fetch("
            SELECT slug, title FROM works
            WHERE id < :current_id AND is_published = 1
            ORDER BY id DESC
            LIMIT 1
        ", ['current_id' => $work['id']]);

        $nextWork = $db->fetch("
            SELECT slug, title FROM works
            WHERE id > :current_id AND is_published = 1
            ORDER BY id ASC
            LIMIT 1
        ", ['current_id' => $work['id']]);

        // SEO設定
        $seo = Seo::createForWork($work);

        // 構造化データ
        $schemas = [
            Schema::work($work, $images)
        ];

        // パンくずリスト
        $breadcrumbs = [
            ['name' => 'ホーム', 'url' => ''],
            ['name' => '施工実績', 'url' => 'works'],
            ['name' => $work['category_name'], 'url' => 'works?category=' . $work['category_slug']],
            ['name' => $work['title']]
        ];

        $schemas[] = Schema::breadcrumb($breadcrumbs);
        $schema = implode("\n", $schemas);

        // ビューに渡すデータ
        $data = [
            'page' => 'works/show',
            'seo' => $seo,
            'schema' => $schema,
            'work' => $work,
            'images' => $images,
            'workTags' => $workTags,
            'relatedWorks' => $relatedWorks,
            'prevWork' => $prevWork,
            'nextWork' => $nextWork,
            'breadcrumbs' => $breadcrumbs,
            'bodyClass' => 'page-works page-works-show'
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

    private function render404()
    {
        header("HTTP/1.0 404 Not Found");

        $data = [
            'page' => '404',
            'title' => '404 Not Found',
            'bodyClass' => 'page-404'
        ];

        return $this->render($data);
    }
}