<?php

class Admin_RecruitController extends Controller
{
    public function __construct()
    {
        // 認証チェック
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            redirect('admin/login');
        }
    }

    public function index()
    {
        $db = Db::getInstance();

        // 採用情報設定を取得
        $settingsData = $db->fetchAll("SELECT `key`, `value` FROM recruit_settings");
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
    <title>採用情報管理 | 管理画面</title>
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
            min-height: 100px;
            resize: vertical;
        }

        .form-group input[type="checkbox"] {
            margin-right: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
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

        .help-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        .success-message {
            background: #10b981;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
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
            <h1>採用情報管理</h1>
            <a href="/admin/dashboard" class="btn btn-back">← 管理画面に戻る</a>
        </div>

        <form method="POST" action="/admin/recruit/update">
            <!-- ページヘッダー -->
            <div class="form-section">
                <h2>ページヘッダー</h2>
                <div class="form-group">
                    <label>ページタイトル</label>
                    <input type="text" name="page_title" value="' . h($settings['page_title'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>サブタイトル</label>
                    <input type="text" name="page_subtitle" value="' . h($settings['page_subtitle'] ?? '') . '">
                </div>
            </div>

            <!-- 職種1 -->
            <div class="form-section">
                <h2>募集職種 1</h2>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="job1_enabled" value="1" ' . (($settings['job1_enabled'] ?? '1') == '1' ? 'checked' : '') . '>
                        この職種を表示する
                    </label>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>アイコン</label>
                        <input type="text" name="job1_icon" value="' . h($settings['job1_icon'] ?? '') . '">
                        <div class="help-text">絵文字を入力してください（例: 🌳）</div>
                    </div>
                    <div class="form-group">
                        <label>職種名</label>
                        <input type="text" name="job1_title" value="' . h($settings['job1_title'] ?? '') . '">
                    </div>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="job1_description">' . h($settings['job1_description'] ?? '') . '</textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>雇用形態</label>
                        <input type="text" name="job1_employment_type" value="' . h($settings['job1_employment_type'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>給与</label>
                        <input type="text" name="job1_salary" value="' . h($settings['job1_salary'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>勤務時間</label>
                        <input type="text" name="job1_work_hours" value="' . h($settings['job1_work_hours'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>休日</label>
                        <input type="text" name="job1_holidays" value="' . h($settings['job1_holidays'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>必要資格</label>
                        <input type="text" name="job1_qualifications" value="' . h($settings['job1_qualifications'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>経験</label>
                        <input type="text" name="job1_experience" value="' . h($settings['job1_experience'] ?? '') . '">
                    </div>
                </div>
            </div>

            <!-- 職種2 -->
            <div class="form-section">
                <h2>募集職種 2</h2>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="job2_enabled" value="1" ' . (($settings['job2_enabled'] ?? '1') == '1' ? 'checked' : '') . '>
                        この職種を表示する
                    </label>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>アイコン</label>
                        <input type="text" name="job2_icon" value="' . h($settings['job2_icon'] ?? '') . '">
                        <div class="help-text">絵文字を入力してください（例: ✂️）</div>
                    </div>
                    <div class="form-group">
                        <label>職種名</label>
                        <input type="text" name="job2_title" value="' . h($settings['job2_title'] ?? '') . '">
                    </div>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="job2_description">' . h($settings['job2_description'] ?? '') . '</textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>雇用形態</label>
                        <input type="text" name="job2_employment_type" value="' . h($settings['job2_employment_type'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>給与</label>
                        <input type="text" name="job2_salary" value="' . h($settings['job2_salary'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>勤務時間</label>
                        <input type="text" name="job2_work_hours" value="' . h($settings['job2_work_hours'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>休日</label>
                        <input type="text" name="job2_holidays" value="' . h($settings['job2_holidays'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>必要資格</label>
                        <input type="text" name="job2_qualifications" value="' . h($settings['job2_qualifications'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>経験</label>
                        <input type="text" name="job2_experience" value="' . h($settings['job2_experience'] ?? '') . '">
                    </div>
                </div>
            </div>

            <!-- 福利厚生 -->
            <div class="form-section">
                <h2>福利厚生</h2>
                <div class="form-group">
                    <label>福利厚生一覧</label>
                    <textarea name="benefits" style="min-height: 200px;">' . h($settings['benefits'] ?? '') . '</textarea>
                    <div class="help-text">1行につき1項目。「タイトル|説明」の形式で入力してください。</div>
                </div>
            </div>

            <!-- 求める人物像 -->
            <div class="form-section">
                <h2>求める人物像</h2>
                <div class="form-group">
                    <label>求める人物像一覧</label>
                    <textarea name="requirements" style="min-height: 200px;">' . h($settings['requirements'] ?? '') . '</textarea>
                    <div class="help-text">1行につき1項目。「タイトル|説明」の形式で入力してください。</div>
                </div>
            </div>

            <!-- CTA -->
            <div class="form-section">
                <h2>応募セクション</h2>
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="cta_title" value="' . h($settings['cta_title'] ?? '') . '">
                </div>
                <div class="form-group">
                    <label>説明文</label>
                    <textarea name="cta_description">' . h($settings['cta_description'] ?? '') . '</textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>ボタンテキスト</label>
                        <input type="text" name="cta_button_text" value="' . h($settings['cta_button_text'] ?? '') . '">
                    </div>
                    <div class="form-group">
                        <label>ボタンリンク先</label>
                        <input type="text" name="cta_button_url" value="' . h($settings['cta_button_url'] ?? '') . '">
                    </div>
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
            redirect('admin/recruit');
        }

        $db = Db::getInstance();

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
}
