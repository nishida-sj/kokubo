<?php

namespace Admin;

class RecruitController extends \Controller
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

        // 採用情報設定を取得
        $settingsData = $db->fetchAll("SELECT `key`, `value` FROM recruit_settings");
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
            'page' => 'admin/pages/recruit/index',
            'title' => '採用情報管理',
            'settings' => $settings,
            'successMessage' => $successMessage
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/recruit');
        }

        $db = \Db::getInstance();

        try {
            $db->beginTransaction();

            // 全ての設定を更新
            $fields = [
                'page_title', 'page_subtitle',
                'job1_icon', 'job1_title', 'job1_description', 'job1_employment_type',
                'job1_salary', 'job1_work_hours', 'job1_holidays', 'job1_qualifications', 'job1_experience',
                'job2_icon', 'job2_title', 'job2_description', 'job2_employment_type',
                'job2_salary', 'job2_work_hours', 'job2_holidays', 'job2_qualifications', 'job2_experience',
                'benefits', 'requirements',
                'cta_title', 'cta_description', 'cta_button_text', 'cta_button_url'
            ];

            foreach ($fields as $field) {
                $value = $_POST[$field] ?? '';
                $db->query("INSERT INTO recruit_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?", [$field, $value, $value]);
            }

            // チェックボックス
            $job1_enabled = isset($_POST['job1_enabled']) ? '1' : '0';
            $job2_enabled = isset($_POST['job2_enabled']) ? '1' : '0';

            $db->query("INSERT INTO recruit_settings (`key`, `value`) VALUES ('job1_enabled', ?) ON DUPLICATE KEY UPDATE `value` = ?", [$job1_enabled, $job1_enabled]);
            $db->query("INSERT INTO recruit_settings (`key`, `value`) VALUES ('job2_enabled', ?) ON DUPLICATE KEY UPDATE `value` = ?", [$job2_enabled, $job2_enabled]);

            $db->commit();

            $_SESSION['success_message'] = '採用情報を更新しました。';
            redirect('admin/recruit');

        } catch (Exception $e) {
            $db->rollBack();
            error_log('Recruit update error: ' . $e->getMessage());
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
