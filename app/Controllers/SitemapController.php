<?php
// サイトマップコントローラー

class SitemapController
{
    public function xml()
    {
        $db = Db::getInstance();

        // ヘッダー設定
        header('Content-Type: application/xml; charset=UTF-8');

        // サイトマップの開始
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // 静的ページ
        $staticPages = [
            [
                'url' => site_url(),
                'priority' => '1.0',
                'changefreq' => 'daily'
            ],
            [
                'url' => site_url('works'),
                'priority' => '0.8',
                'changefreq' => 'daily'
            ],
            [
                'url' => site_url('contact'),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ]
        ];

        foreach ($staticPages as $page) {
            echo $this->renderUrlElement($page['url'], null, $page['changefreq'], $page['priority']);
        }

        // 実績ページ
        $works = $db->fetchAll("
            SELECT slug, updated_at
            FROM works
            WHERE is_published = 1
            ORDER BY updated_at DESC
        ");

        foreach ($works as $work) {
            echo $this->renderUrlElement(
                site_url('works/' . $work['slug']),
                $work['updated_at'],
                'monthly',
                '0.6'
            );
        }

        // カテゴリー別実績ページ
        $categories = $db->fetchAll("
            SELECT c.slug, MAX(w.updated_at) as updated_at
            FROM categories c
            INNER JOIN works w ON c.id = w.category_id
            WHERE w.is_published = 1
            GROUP BY c.id, c.slug
        ");

        foreach ($categories as $category) {
            echo $this->renderUrlElement(
                site_url('works?category=' . $category['slug']),
                $category['updated_at'],
                'weekly',
                '0.5'
            );
        }

        // サイトマップの終了
        echo '</urlset>';
    }

    private function renderUrlElement($url, $lastmod = null, $changefreq = 'monthly', $priority = '0.5')
    {
        $xml = "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";

        if ($lastmod) {
            $xml .= "    <lastmod>" . date('Y-m-d\TH:i:s+09:00', strtotime($lastmod)) . "</lastmod>\n";
        }

        $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
        $xml .= "    <priority>{$priority}</priority>\n";
        $xml .= "  </url>\n";

        return $xml;
    }
}