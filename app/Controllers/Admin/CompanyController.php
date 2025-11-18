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

        // 基本レイアウト
        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会社案内管理 | 管理画面</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            color: #2563eb;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-back {
            background: #6b7280;
            color: white;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        .form-section {
            background: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-section h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 2px solid #2563eb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
            font-family: inherit;
        }

        .btn-save {
            background: #2563eb;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .success-message {
            background: #10b981;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">';

        // 成功メッセージ
        if (isset($_SESSION['success_message'])) {
            $html .= '<div class="success-message">' . h($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }

        $html .= '
        <div class="header">
            <h1>会社案内管理</h1>
            <a href="/admin/dashboard" class="btn btn-back">← 管理画面に戻る</a>
        </div>

        <form method="POST" action="/admin/company/update">
            <!-- 代表挨拶 -->
            <div class="form-section">
                <h2>代表挨拶</h2>
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="greeting_title" value="' . h($settings['greeting_title'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>内容</label>
                    <textarea name="greeting_content">' . h($settings['greeting_content'] ?? '') . '</textarea>
                </div>
            </div>

            <!-- 会社概要 -->
            <div class="form-section">
                <h2>会社概要</h2>
                <div class="form-group">
                    <label>会社名</label>
                    <input type="text" name="overview_name" value="' . h($settings['overview_name'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>設立</label>
                    <input type="text" name="overview_established" value="' . h($settings['overview_established'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>代表者</label>
                    <input type="text" name="overview_representative" value="' . h($settings['overview_representative'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>従業員数</label>
                    <input type="text" name="overview_employees" value="' . h($settings['overview_employees'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>事業内容</label>
                    <textarea name="overview_business" style="min-height: 100px;">' . h($settings['overview_business'] ?? '') . '</textarea>
                </div>
                <div class="form-group">
                    <label>対応エリア</label>
                    <input type="text" name="overview_area" value="' . h($settings['overview_area'] ?? '') . '">
                </div>
            </div>

            <div class="form-section">
                <button type="submit" class="btn btn-save">保存する</button>
            </div>
        </form>
    </div>
</body>
</html>';

        return $html;
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
}
