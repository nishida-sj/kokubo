<?php

namespace Admin;

class CompanyController extends \Controller
{
    public function __construct()
    {
        // 認証チェック
        if (!is_admin_logged_in()) {
            header('Location: /admin');
            exit;
        }
    }

    public function index()
    {
        $db = \Db::getInstance();

        // 会社案内設定を取得
        $settingsData = $db->fetchAll("SELECT `key`, `value` FROM company_settings");
        $settings = [];
        foreach ($settingsData as $row) {
            $settings[$row['key']] = $row['value'];
        }

        $successMessage = null;
        if (isset($_SESSION['success_message'])) {
            $successMessage = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }

        return $this->renderAdmin([
            'page' => 'admin/pages/company/index',
            'title' => '会社案内管理',
            'settings' => $settings,
            'successMessage' => $successMessage
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/company');
        }

        $db = \Db::getInstance();

        try {
            $db->beginTransaction();

            // 全ての設定を更新
            $fields = [
                'greeting_title', 'greeting_content',
                'overview_name', 'overview_established', 'overview_representative',
                'overview_employees', 'overview_business', 'overview_area'
            ];

            foreach ($fields as $field) {
                $value = $_POST[$field] ?? '';
                $db->query("INSERT INTO company_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?", [$field, $value, $value]);
            }

            $db->commit();

            $_SESSION['success_message'] = '会社案内を更新しました。';
            redirect('admin/company');

        } catch (Exception $e) {
            $db->rollBack();
            error_log('Company update error: ' . $e->getMessage());
            die('エラーが発生しました: ' . h($e->getMessage()));
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
