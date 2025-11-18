<?php

namespace Admin;

class TagsController extends \Controller
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

        // タグ一覧を取得
        $tags = $db->fetchAll("SELECT * FROM tags ORDER BY display_order ASC, name ASC");

        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ管理 | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        h1 { color: #2E7D32; margin: 0; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2E7D32; color: white; }
        .btn-primary:hover { background: #1B5E20; }
        .btn-secondary { background: #666; color: white; margin-right: 10px; }
        .btn-secondary:hover { background: #555; }
        .btn-danger { background: #d32f2f; color: white; }
        .btn-danger:hover { background: #b71c1c; }
        .btn-edit { background: #1976d2; color: white; padding: 6px 12px; font-size: 14px; }
        .btn-edit:hover { background: #1565c0; }
        .content { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background: #f5f5f5; font-weight: 600; }
        .actions { display: flex; gap: 10px; }
        .empty-state { text-align: center; padding: 40px; color: #666; }
        .success-message { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏷️ タグ管理</h1>
            <div>
                <a href="/admin/dashboard" class="btn btn-secondary">ダッシュボードに戻る</a>
                <a href="/admin/tags/create" class="btn btn-primary">+ 新しいタグを追加</a>
            </div>
        </div>

        <div class="content">';

        if (isset($_SESSION['success_message'])) {
            $html .= '<div class="success-message">' . h($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }

        if (empty($tags)) {
            $html .= '<div class="empty-state">
                <p>タグがまだ登録されていません。</p>
                <a href="/admin/tags/create" class="btn btn-primary">最初のタグを作成</a>
            </div>';
        } else {
            $html .= '<table>
                <thead>
                    <tr>
                        <th style="width: 80px;">表示順</th>
                        <th>タグ名</th>
                        <th style="width: 150px;">作成日</th>
                        <th style="width: 200px;">操作</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($tags as $tag) {
                $html .= '<tr>
                    <td>' . h($tag['display_order']) . '</td>
                    <td>' . h($tag['name']) . '</td>
                    <td>' . date('Y/m/d', strtotime($tag['created_at'])) . '</td>
                    <td>
                        <div class="actions">
                            <a href="/admin/tags/' . $tag['id'] . '/edit" class="btn btn-edit">編集</a>
                            <form method="POST" action="/admin/tags/' . $tag['id'] . '/delete" style="display: inline;" onsubmit="return confirm(\'このタグを削除してもよろしいですか？\');">
                                <input type="hidden" name="csrf_token" value="' . csrf_token() . '">
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </div>
                    </td>
                </tr>';
            }

            $html .= '</tbody>
            </table>';
        }

        $html .= '</div>
    </div>
</body>
</html>';

        return $html;
    }

    public function create()
    {
        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ追加 | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; margin: 0; }
        .content { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 10px; }
        .btn-primary { background: #2E7D32; color: white; }
        .btn-primary:hover { background: #1B5E20; }
        .btn-secondary { background: #666; color: white; }
        .btn-secondary:hover { background: #555; }
        .help-text { font-size: 14px; color: #666; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏷️ タグ追加</h1>
        </div>

        <div class="content">
            <form method="POST" action="/admin/tags/store">
                <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

                <div class="form-group">
                    <label for="name">タグ名 *</label>
                    <input type="text" id="name" name="name" required placeholder="例: 植樹工事">
                </div>

                <div class="form-group">
                    <label for="display_order">表示順</label>
                    <input type="number" id="display_order" name="display_order" value="0" min="0">
                    <div class="help-text">数字が小さいほど上に表示されます</div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">保存</button>
                    <a href="/admin/tags" class="btn btn-secondary">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/tags');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $name = trim($_POST['name'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            die('タグ名を入力してください。');
        }

        $db = \Db::getInstance();

        try {
            $db->insert('tags', [
                'name' => $name,
                'display_order' => $displayOrder
            ]);

            $_SESSION['success_message'] = 'タグを追加しました。';
            redirect('admin/tags');

        } catch (\Exception $e) {
            die('エラーが発生しました: ' . h($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $db = \Db::getInstance();
        $tag = $db->fetch("SELECT * FROM tags WHERE id = ?", [$id]);

        if (!$tag) {
            die('タグが見つかりません。');
        }

        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ編集 | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; margin: 0; }
        .content { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 10px; }
        .btn-primary { background: #2E7D32; color: white; }
        .btn-primary:hover { background: #1B5E20; }
        .btn-secondary { background: #666; color: white; }
        .btn-secondary:hover { background: #555; }
        .help-text { font-size: 14px; color: #666; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏷️ タグ編集</h1>
        </div>

        <div class="content">
            <form method="POST" action="/admin/tags/' . $id . '/update">
                <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

                <div class="form-group">
                    <label for="name">タグ名 *</label>
                    <input type="text" id="name" name="name" required value="' . h($tag['name']) . '">
                </div>

                <div class="form-group">
                    <label for="display_order">表示順</label>
                    <input type="number" id="display_order" name="display_order" value="' . h($tag['display_order']) . '" min="0">
                    <div class="help-text">数字が小さいほど上に表示されます</div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">更新</button>
                    <a href="/admin/tags" class="btn btn-secondary">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/tags');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $name = trim($_POST['name'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            die('タグ名を入力してください。');
        }

        $db = \Db::getInstance();

        try {
            $db->update('tags', [
                'name' => $name,
                'display_order' => $displayOrder
            ], ['id' => $id]);

            $_SESSION['success_message'] = 'タグを更新しました。';
            redirect('admin/tags');

        } catch (\Exception $e) {
            die('エラーが発生しました: ' . h($e->getMessage()));
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/tags');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $db = \Db::getInstance();

        try {
            $db->delete('tags', ['id' => $id]);

            $_SESSION['success_message'] = 'タグを削除しました。';
            redirect('admin/tags');

        } catch (\Exception $e) {
            die('エラーが発生しました: ' . h($e->getMessage()));
        }
    }
}
