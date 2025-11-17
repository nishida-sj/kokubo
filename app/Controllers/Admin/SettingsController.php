<?php
// 管理画面サイト設定コントローラー

class Admin_SettingsController
{
    public function index()
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // 現在の設定を取得
        $settings = [];
        $settingsData = $db->fetchAll("SELECT `key`, `value` FROM site_settings");
        foreach ($settingsData as $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        $data = [
            'page' => 'admin/pages/settings/index',
            'title' => 'サイト設定',
            'settings' => $settings
        ];

        return $this->renderAdmin($data);
    }

    public function update()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/settings');
            return;
        }

        $db = Db::getInstance();
        $errors = [];

        try {
            Csrf::requireToken();

            // 入力データ取得
            $inputData = [
                'site_title' => trim($_POST['site_title'] ?? ''),
                'site_description' => trim($_POST['site_description'] ?? ''),
                'site_keywords' => trim($_POST['site_keywords'] ?? ''),
                'company_name' => trim($_POST['company_name'] ?? ''),
                'company_postal_code' => trim($_POST['company_postal_code'] ?? ''),
                'company_address' => trim($_POST['company_address'] ?? ''),
                'company_tel' => trim($_POST['company_tel'] ?? ''),
                'company_fax' => trim($_POST['company_fax'] ?? ''),
                'company_email' => trim($_POST['company_email'] ?? ''),
                'notification_email' => trim($_POST['notification_email'] ?? ''),
                'company_business_hours' => trim($_POST['company_business_hours'] ?? ''),
                'company_holiday' => trim($_POST['company_holiday'] ?? ''),
                'company_established' => trim($_POST['company_established'] ?? ''),
                'company_license' => trim($_POST['company_license'] ?? ''),
                'google_analytics_id' => trim($_POST['google_analytics_id'] ?? ''),
                'google_tag_manager_id' => trim($_POST['google_tag_manager_id'] ?? ''),
                'facebook_url' => trim($_POST['facebook_url'] ?? ''),
                'instagram_url' => trim($_POST['instagram_url'] ?? ''),
                'twitter_url' => trim($_POST['twitter_url'] ?? ''),
                'youtube_url' => trim($_POST['youtube_url'] ?? ''),
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
                'maintenance_message' => trim($_POST['maintenance_message'] ?? '')
            ];

            // バリデーション
            $validator = new Validator($inputData);
            $isValid = $validator->validate([
                'site_title' => 'required|max:100',
                'site_description' => 'required|max:300',
                'site_keywords' => 'max:500',
                'company_name' => 'required|max:100',
                'company_postal_code' => 'max:10',
                'company_address' => 'required|max:200',
                'company_tel' => 'required|max:20',
                'company_fax' => 'max:20',
                'company_email' => 'required|email|max:100',
                'notification_email' => 'email|max:100',
                'company_business_hours' => 'max:100',
                'company_holiday' => 'max:100',
                'company_established' => 'max:50',
                'company_license' => 'max:200',
                'google_analytics_id' => 'max:50',
                'google_tag_manager_id' => 'max:50',
                'facebook_url' => 'url|max:200',
                'instagram_url' => 'url|max:200',
                'twitter_url' => 'url|max:200',
                'youtube_url' => 'url|max:200',
                'maintenance_message' => 'max:1000'
            ]);

            if (!$isValid) {
                $errors = $validator->getErrors();
            }

            if (empty($errors)) {
                $db->beginTransaction();

                try {
                    // 設定を更新
                    foreach ($inputData as $key => $value) {
                        $existing = $db->fetch(
                            "SELECT `key` FROM site_settings WHERE `key` = :key",
                            ['key' => $key]
                        );

                        if ($existing) {
                            $db->update(
                                'site_settings',
                                ['value' => $value],
                                '`key` = :key',
                                ['key' => $key]
                            );
                        } else {
                            $db->insert('site_settings', [
                                'key' => $key,
                                'value' => $value
                            ]);
                        }
                    }

                    $db->commit();
                    redirect('admin/settings');
                    return;

                } catch (Exception $e) {
                    $db->rollBack();
                    throw $e;
                }
            }

        } catch (Exception $e) {
            error_log('Settings update error: ' . $e->getMessage());

            // エラーの詳細を表示（開発環境では詳細、本番では一般的なメッセージ）
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                $errors['general'] = [
                    '設定の更新中にエラーが発生しました。',
                    'エラー詳細: ' . $e->getMessage(),
                    'ファイル: ' . $e->getFile() . ':' . $e->getLine()
                ];
            } else {
                $errors['general'] = [
                    '設定の更新中にエラーが発生しました。',
                    'エラー: ' . $e->getMessage()
                ];
            }
        }

        // エラー時は設定ページを再表示
        $data = [
            'page' => 'admin/pages/settings/index',
            'title' => 'サイト設定',
            'settings' => $inputData,
            'errors' => $errors
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