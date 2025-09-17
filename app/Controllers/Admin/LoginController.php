<?php
// 管理画面ログインコントローラー

class Admin_LoginController
{
    public function index()
    {
        // 既にログイン済みの場合はダッシュボードへリダイレクト
        if (Auth::isLoggedIn()) {
            redirect('admin/dashboard');
            return;
        }

        // ログインページを表示
        $data = [
            'page' => 'admin/login',
            'title' => 'ログイン - 管理画面',
            'bodyClass' => 'admin-login'
        ];

        return $this->renderAdmin($data);
    }

    public function login()
    {
        // POSTリクエストでない場合はリダイレクト
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin');
            return;
        }

        $errors = [];
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        try {
            // CSRFトークン検証
            Csrf::requireToken();

            // バリデーション
            if (empty($username)) {
                $errors[] = 'ユーザー名を入力してください。';
            }

            if (empty($password)) {
                $errors[] = 'パスワードを入力してください。';
            }

            // ログイン認証
            if (empty($errors)) {
                if (Auth::login($username, $password)) {
                    // ログイン成功
                    redirect('admin/dashboard');
                    return;
                } else {
                    $errors[] = 'ユーザー名またはパスワードが正しくありません。';
                }
            }

        } catch (Exception $e) {
            error_log('Admin login error: ' . $e->getMessage());
            $errors[] = 'システムエラーが発生しました。';
        }

        // ログイン失敗時はログインページを再表示
        $data = [
            'page' => 'admin/login',
            'title' => 'ログイン - 管理画面',
            'bodyClass' => 'admin-login',
            'errors' => $errors,
            'username' => $username
        ];

        return $this->renderAdmin($data);
    }

    public function logout()
    {
        Auth::logout();
        redirect('admin');
    }

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/auth.php';
        return ob_get_clean();
    }
}