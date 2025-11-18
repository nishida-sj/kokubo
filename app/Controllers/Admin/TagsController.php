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

        $successMessage = null;
        if (isset($_SESSION['success_message'])) {
            $successMessage = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }

        return $this->renderAdmin([
            'page' => 'admin/pages/tags/index',
            'title' => 'タグ管理',
            'tags' => $tags,
            'hasDisplayOrder' => true,
            'successMessage' => $successMessage
        ]);
    }

    public function create()
    {
        return $this->renderAdmin([
            'page' => 'admin/pages/tags/create',
            'title' => 'タグ追加'
        ]);
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

        return $this->renderAdmin([
            'page' => 'admin/pages/tags/edit',
            'title' => 'タグ編集',
            'tag' => $tag
        ]);
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

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/main.php';
        return ob_get_clean();
    }
}
