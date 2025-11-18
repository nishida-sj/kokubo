<?php

namespace Admin;

class DashboardController extends \Controller
{
    public function index()
    {
        // ログインチェック
        if (!is_admin_logged_in()) {
            header('Location: /admin');
            exit;
        }

        try {
            $db = \Database::getInstance();

            // 統計情報を取得
            $worksCount = $db->fetch("SELECT COUNT(*) as count FROM works")['count'] ?? 0;
            $categoriesCount = $db->fetch("SELECT COUNT(*) as count FROM categories")['count'] ?? 0;
            $contactsCount = $db->fetch("SELECT COUNT(*) as count FROM contacts")['count'] ?? 0;

            return $this->renderAdmin([
                'page' => 'admin/pages/dashboard/index',
                'title' => 'ダッシュボード',
                'worksCount' => $worksCount,
                'categoriesCount' => $categoriesCount,
                'contactsCount' => $contactsCount
            ]);

        } catch (\Exception $e) {
            return '<h1>Dashboard Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/main.php';
        return ob_get_clean();
    }
}
