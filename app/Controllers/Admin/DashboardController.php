<?php
// 管理画面ダッシュボードコントローラー

class Admin_DashboardController
{
    public function index()
    {
        // ログイン確認
        Auth::requireLogin();

        $db = Db::getInstance();

        // 統計データを取得
        $stats = [
            'total_works' => $db->count('works'),
            'published_works' => $db->count('works', 'is_published = 1'),
            'featured_works' => $db->count('works', 'is_featured = 1'),
            'total_contacts' => $db->count('contacts'),
            'unread_contacts' => $db->count('contacts', 'is_read = 0'),
            'total_categories' => $db->count('categories'),
        ];

        // 最近の実績（5件）
        $recentWorks = $db->fetchAll("
            SELECT w.*, c.name as category_name
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            ORDER BY w.created_at DESC
            LIMIT 5
        ");

        // 最近のお問い合わせ（5件）
        $recentContacts = $db->fetchAll("
            SELECT * FROM contacts
            ORDER BY created_at DESC
            LIMIT 5
        ");

        // 月別実績投稿数（過去12ヶ月）
        $monthlyStats = $db->fetchAll("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
            FROM works
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");

        // カテゴリー別実績数
        $categoryStats = $db->fetchAll("
            SELECT
                c.name,
                COUNT(w.id) as count
            FROM categories c
            LEFT JOIN works w ON c.id = w.category_id AND w.is_published = 1
            GROUP BY c.id, c.name
            ORDER BY count DESC
        ");

        $data = [
            'page' => 'admin/dashboard',
            'title' => 'ダッシュボード',
            'stats' => $stats,
            'recentWorks' => $recentWorks,
            'recentContacts' => $recentContacts,
            'monthlyStats' => $monthlyStats,
            'categoryStats' => $categoryStats
        ];

        return $this->renderAdmin($data);
    }

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/main.php';
        return ob_get_clean();
    }
}