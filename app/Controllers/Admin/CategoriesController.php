<?php

namespace Admin;

class CategoriesController extends \Controller
{
    public function __construct()
    {
        if (!is_admin_logged_in()) {
            header('Location: /admin');
            exit;
        }
    }

    public function index()
    {
        $db = \Db::getInstance();

        // display_orderカラムの存在を確認
        $columns = $db->fetchAll("SHOW COLUMNS FROM categories");
        $hasDisplayOrder = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'display_order') {
                $hasDisplayOrder = true;
                break;
            }
        }

        // カテゴリー一覧を取得
        if ($hasDisplayOrder) {
            $categories = $db->fetchAll("SELECT * FROM categories ORDER BY display_order ASC, name ASC");
        } else {
            $categories = $db->fetchAll("SELECT * FROM categories ORDER BY name ASC");
        }

        $successMessage = null;
        if (isset($_SESSION['success_message'])) {
            $successMessage = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }

        return $this->renderAdmin([
            'page' => 'admin/pages/categories/index',
            'title' => 'カテゴリー管理',
            'categories' => $categories,
            'hasDisplayOrder' => $hasDisplayOrder,
            'successMessage' => $successMessage
        ]);
    }

    public function create()
    {
        return $this->renderAdmin([
            'page' => 'admin/pages/categories/create',
            'title' => 'カテゴリー追加'
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/categories');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $name = trim($_POST['name'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            die('カテゴリー名を入力してください。');
        }

        $db = \Db::getInstance();

        try {
            $columns = $db->fetchAll("SHOW COLUMNS FROM categories");
            $hasDisplayOrder = false;
            foreach ($columns as $column) {
                if ($column['Field'] === 'display_order') {
                    $hasDisplayOrder = true;
                    break;
                }
            }

            if ($hasDisplayOrder) {
                $db->insert('categories', ['name' => $name, 'display_order' => $displayOrder]);
            } else {
                $db->insert('categories', ['name' => $name]);
            }

            $_SESSION['success_message'] = 'カテゴリーを追加しました。';
            redirect('admin/categories');

        } catch (\Exception $e) {
            die('エラーが発生しました: ' . h($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $db = \Db::getInstance();
        $category = $db->fetch("SELECT * FROM categories WHERE id = ?", [$id]);

        if (!$category) {
            die('カテゴリーが見つかりません。');
        }

        return $this->renderAdmin([
            'page' => 'admin/pages/categories/edit',
            'title' => 'カテゴリー編集',
            'category' => $category
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/categories');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $name = trim($_POST['name'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            die('カテゴリー名を入力してください。');
        }

        $db = \Db::getInstance();

        try {
            $columns = $db->fetchAll("SHOW COLUMNS FROM categories");
            $hasDisplayOrder = false;
            foreach ($columns as $column) {
                if ($column['Field'] === 'display_order') {
                    $hasDisplayOrder = true;
                    break;
                }
            }

            if ($hasDisplayOrder) {
                $db->update('categories', ['name' => $name, 'display_order' => $displayOrder], ['id' => $id]);
            } else {
                $db->update('categories', ['name' => $name], ['id' => $id]);
            }

            $_SESSION['success_message'] = 'カテゴリーを更新しました。';
            redirect('admin/categories');

        } catch (\Exception $e) {
            die('エラーが発生しました: ' . h($e->getMessage()));
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/categories');
        }

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            die('不正なリクエストです。');
        }

        $db = \Db::getInstance();

        try {
            $db->delete('categories', ['id' => $id]);
            $_SESSION['success_message'] = 'カテゴリーを削除しました。';
            redirect('admin/categories');

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
