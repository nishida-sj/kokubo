<?php
// 管理画面実績管理コントローラー

class Admin_WorksController
{
    public function index()
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // フィルター・検索パラメータ取得
        $categoryId = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['q'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at';
        $order = $_GET['order'] ?? 'desc';
        $page = max(1, (int)($_GET['page'] ?? 1));

        // SQLクエリ構築
        $whereConditions = ['1=1'];
        $params = [];

        if (!empty($categoryId)) {
            $whereConditions[] = 'w.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($status === 'published') {
            $whereConditions[] = 'w.is_published = 1';
        } elseif ($status === 'draft') {
            $whereConditions[] = 'w.is_published = 0';
        } elseif ($status === 'featured') {
            $whereConditions[] = 'w.is_featured = 1';
        }

        if (!empty($search)) {
            $whereConditions[] = '(w.title LIKE :search OR w.description LIKE :search OR w.location LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = implode(' AND ', $whereConditions);

        // ソート設定
        $allowedSorts = ['title', 'category_name', 'is_published', 'is_featured', 'created_at', 'updated_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        if (!in_array($order, ['asc', 'desc'])) $order = 'desc';

        $sql = "
            SELECT w.*, c.name as category_name
            FROM works w
            LEFT JOIN categories c ON w.category_id = c.id
            WHERE {$whereClause}
            ORDER BY {$sort} {$order}
        ";

        $pagination = $db->getPagination($sql, $params, $page, ADMIN_ITEMS_PER_PAGE);

        // カテゴリー一覧取得
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");

        $data = [
            'page' => 'admin/pages/works/index',
            'title' => '施工実績管理',
            'works' => $pagination['data'],
            'pagination' => $pagination,
            'categories' => $categories,
            'filters' => [
                'category' => $categoryId,
                'status' => $status,
                'search' => $search,
                'sort' => $sort,
                'order' => $order
            ]
        ];

        return $this->renderAdmin($data);
    }

    public function create()
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // カテゴリー一覧取得
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");

        // タグ一覧取得
        $tags = $db->fetchAll("SELECT * FROM tags ORDER BY name ASC");

        $data = [
            'page' => 'admin/pages/works/create',
            'title' => '実績追加',
            'categories' => $categories,
            'tags' => $tags
        ];

        return $this->renderAdmin($data);
    }

    public function store()
    {
        // デバッグ: リクエスト到達確認
        error_log('WorksController::store() called');
        error_log('REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);
        error_log('POST data: ' . print_r($_POST, true));

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log('Not POST request, redirecting');
            redirect('admin/works');
            return;
        }

        $db = Db::getInstance();
        $errors = [];

        try {
            error_log('Verifying CSRF token');
            Csrf::requireToken();

            // 入力データ取得
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'construction_period' => trim($_POST['construction_period'] ?? ''),
                'floor_area' => trim($_POST['floor_area'] ?? ''),
                'structure' => trim($_POST['structure'] ?? ''),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'meta_title' => trim($_POST['meta_title'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? '')
            ];

            // スラッグ自動生成
            if (empty($data['slug'])) {
                $data['slug'] = generate_slug($data['title']);
            }

            // バリデーション
            $validator = new Validator($data);
            $isValid = $validator->validate([
                'title' => 'required|max:200',
                'slug' => 'required|max:200|slug|unique:works,slug',
                'category_id' => 'required|exists:categories,id',
                'description' => 'max:1000',
                'location' => 'max:200',
                'construction_period' => 'max:100',
                'floor_area' => 'max:100',
                'structure' => 'max:100',
                'meta_title' => 'max:200',
                'meta_description' => 'max:500'
            ]);

            if (!$isValid) {
                $errors = $validator->getErrors();
            }

            // メイン画像アップロード
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadResult = ImageTool::upload('main_image', 'works');
                    $data['main_image'] = $uploadResult['path'];
                } catch (Exception $e) {
                    $errors['main_image'] = [$e->getMessage()];
                }
            }

            if (empty($errors)) {
                $db->beginTransaction();

                try {
                    // 実績を保存
                    error_log('Inserting work into database');
                    $workId = $db->insert('works', $data);
                    error_log('Work inserted with ID: ' . $workId);

                    // タグを保存
                    if (!empty($_POST['tags'])) {
                        error_log('Saving tags');
                        $tagIds = array_filter(array_map('intval', $_POST['tags']));
                        foreach ($tagIds as $tagId) {
                            $db->insert('work_tags', [
                                'work_id' => $workId,
                                'tag_id' => $tagId
                            ]);
                        }
                    }

                    // 追加画像アップロード
                    if (!empty($_FILES['images']['name'][0])) {
                        error_log('Uploading additional images');
                        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                                // ファイル配列を再構築
                                $file = [
                                    'name' => $_FILES['images']['name'][$i],
                                    'type' => $_FILES['images']['type'][$i],
                                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                                    'error' => $_FILES['images']['error'][$i],
                                    'size' => $_FILES['images']['size'][$i]
                                ];

                                $_FILES['temp_image'] = $file;

                                try {
                                    $uploadResult = ImageTool::upload('temp_image', 'works');
                                    $db->insert('work_images', [
                                        'work_id' => $workId,
                                        'path' => $uploadResult['path'],
                                        'path_thumb' => $uploadResult['thumbnail_path'] ?? null,
                                        'alt' => $_POST['image_alts'][$i] ?? '',
                                        'sort_order' => $i,
                                        'is_before' => isset($_POST['image_is_before'][$i]) ? 1 : 0
                                    ]);
                                } catch (Exception $e) {
                                    // 個別画像のエラーはログに記録して続行
                                    error_log('Image upload error: ' . $e->getMessage());
                                }
                            }
                        }
                    }

                    $db->commit();
                    error_log('Transaction committed successfully');
                    error_log('Redirecting to admin/works');
                    redirect('admin/works');
                    return;

                } catch (Exception $e) {
                    $db->rollBack();
                    throw $e;
                }
            }

        } catch (Exception $e) {
            error_log('Work create error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $errors['general'] = ['保存中にエラーが発生しました。'];
        }

        // エラー時は作成ページを再表示
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");
        $tags = $db->fetchAll("SELECT * FROM tags ORDER BY name ASC");

        $data = [
            'page' => 'admin/pages/works/create',
            'title' => '実績追加',
            'categories' => $categories,
            'tags' => $tags,
            'errors' => $errors,
            'formData' => $data
        ];

        return $this->renderAdmin($data);
    }

    public function edit($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // 実績取得
        $work = $db->fetch("SELECT * FROM works WHERE id = :id", ['id' => $id]);
        if (!$work) {
            redirect('admin/works');
            return;
        }

        // カテゴリー一覧取得
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");

        // タグ一覧取得
        $tags = $db->fetchAll("SELECT * FROM tags ORDER BY name ASC");

        // この実績に関連するタグ取得
        $workTagIds = $db->fetchAll("SELECT tag_id FROM work_tags WHERE work_id = :work_id", ['work_id' => $id]);
        $workTagIds = array_column($workTagIds, 'tag_id');

        // 実績画像取得
        $workImages = $db->fetchAll("
            SELECT * FROM work_images
            WHERE work_id = :work_id
            ORDER BY sort_order ASC, id ASC
        ", ['work_id' => $id]);

        $data = [
            'page' => 'admin/pages/works/edit',
            'title' => '実績編集',
            'work' => $work,
            'categories' => $categories,
            'tags' => $tags,
            'workTagIds' => $workTagIds,
            'workImages' => $workImages
        ];

        return $this->renderAdmin($data);
    }

    public function update($id)
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/works');
            return;
        }

        $db = Db::getInstance();

        // 実績存在確認
        $work = $db->fetch("SELECT * FROM works WHERE id = :id", ['id' => $id]);
        if (!$work) {
            redirect('admin/works');
            return;
        }

        $errors = [];

        try {
            Csrf::requireToken();

            // 入力データ取得
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'construction_period' => trim($_POST['construction_period'] ?? ''),
                'floor_area' => trim($_POST['floor_area'] ?? ''),
                'structure' => trim($_POST['structure'] ?? ''),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'meta_title' => trim($_POST['meta_title'] ?? ''),
                'meta_description' => trim($_POST['meta_description'] ?? '')
            ];

            // スラッグ自動生成
            if (empty($data['slug'])) {
                $data['slug'] = generate_slug($data['title']);
            }

            // バリデーション（既存レコードは除外）
            $validator = new Validator($data);
            $isValid = $validator->validate([
                'title' => 'required|max:200',
                'slug' => 'required|max:200|slug',
                'category_id' => 'required|exists:categories,id',
                'description' => 'max:1000',
                'location' => 'max:200',
                'construction_period' => 'max:100',
                'floor_area' => 'max:100',
                'structure' => 'max:100',
                'meta_title' => 'max:200',
                'meta_description' => 'max:500'
            ]);

            // スラッグ重複チェック（自分以外）
            if ($db->exists('works', 'slug = :slug AND id != :id', ['slug' => $data['slug'], 'id' => $id])) {
                $errors['slug'] = ['このスラッグは既に使用されています。'];
                $isValid = false;
            }

            if (!$isValid && empty($errors)) {
                $errors = $validator->getErrors();
            }

            // メイン画像アップロード
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadResult = ImageTool::upload('main_image', 'works');

                    // 古い画像を削除
                    if (!empty($work['main_image'])) {
                        ImageTool::deleteFile(PUBLIC_PATH . $work['main_image']);
                    }

                    $data['main_image'] = $uploadResult['path'];
                } catch (Exception $e) {
                    $errors['main_image'] = [$e->getMessage()];
                }
            }

            if (empty($errors)) {
                $db->beginTransaction();

                try {
                    // 実績を更新
                    $db->update('works', $data, 'id = :id', ['id' => $id]);

                    // 既存のタグ関連を削除
                    $db->delete('work_tags', 'work_id = :work_id', ['work_id' => $id]);

                    // 新しいタグを保存
                    if (!empty($_POST['tags'])) {
                        $tagIds = array_filter(array_map('intval', $_POST['tags']));
                        foreach ($tagIds as $tagId) {
                            $db->insert('work_tags', [
                                'work_id' => $id,
                                'tag_id' => $tagId
                            ]);
                        }
                    }

                    $db->commit();
                    redirect('admin/works');
                    return;

                } catch (Exception $e) {
                    $db->rollBack();
                    throw $e;
                }
            }

        } catch (Exception $e) {
            error_log('Work update error: ' . $e->getMessage());
            $errors['general'] = ['更新中にエラーが発生しました。'];
        }

        // エラー時は編集ページを再表示
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY sort_order ASC, name ASC");
        $tags = $db->fetchAll("SELECT * FROM tags ORDER BY name ASC");
        $workTagIds = $db->fetchAll("SELECT tag_id FROM work_tags WHERE work_id = :work_id", ['work_id' => $id]);
        $workTagIds = array_column($workTagIds, 'tag_id');
        $workImages = $db->fetchAll("SELECT * FROM work_images WHERE work_id = :work_id ORDER BY sort_order ASC", ['work_id' => $id]);

        $dataView = [
            'page' => 'admin/pages/works/edit',
            'title' => '実績編集',
            'work' => array_merge($work, $data),
            'categories' => $categories,
            'tags' => $tags,
            'workTagIds' => $workTagIds,
            'workImages' => $workImages,
            'errors' => $errors
        ];

        return $this->renderAdmin($dataView);
    }

    public function delete($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // 実績存在確認
        $work = $db->fetch("SELECT * FROM works WHERE id = :id", ['id' => $id]);
        if (!$work) {
            redirect('admin/works');
            return;
        }

        try {
            $db->beginTransaction();

            // 関連画像取得
            $images = $db->fetchAll("SELECT * FROM work_images WHERE work_id = :work_id", ['work_id' => $id]);

            // 関連データ削除
            $db->delete('work_tags', 'work_id = :work_id', ['work_id' => $id]);
            $db->delete('work_images', 'work_id = :work_id', ['work_id' => $id]);
            $db->delete('works', 'id = :id', ['id' => $id]);

            // 画像ファイル削除
            if (!empty($work['main_image'])) {
                ImageTool::deleteFile(PUBLIC_PATH . $work['main_image']);
            }

            foreach ($images as $image) {
                ImageTool::deleteFile(PUBLIC_PATH . $image['path']);
                if (!empty($image['path_thumb'])) {
                    ImageTool::deleteFile(PUBLIC_PATH . $image['path_thumb']);
                }
            }

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            error_log('Work delete error: ' . $e->getMessage());
        }

        redirect('admin/works');
    }

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/main.php';
        return ob_get_clean();
    }
}