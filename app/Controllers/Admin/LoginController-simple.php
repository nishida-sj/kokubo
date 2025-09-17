<?php

namespace Admin;

class LoginController extends \Controller
{
    public function index()
    {
        // すでにログイン済みの場合はダッシュボードへ
        if (is_admin_logged_in()) {
            header('Location: /admin/dashboard');
            exit;
        }

        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面ログイン | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 40px; background: #f8f9fa; }
        .container { max-width: 400px; margin: 100px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; text-align: center; margin-bottom: 30px; }
        .form-group { margin: 20px 0; }
        label { display: block; margin-bottom: 5px; color: #333; font-weight: 500; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn { background: #2E7D32; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%; }
        .btn:hover { background: #1B5E20; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0; color: #1565c0; }
        .nav { text-align: center; margin-top: 20px; }
        .nav a { color: #2E7D32; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 管理画面ログイン</h1>

        <div class="info">
            <strong>ログイン情報:</strong><br>
            ユーザー名: admin<br>
            パスワード: admin123
        </div>

        <form method="POST" action="/admin/login">
            <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">ログイン</button>
        </form>

        <div class="nav">
            <a href="/">← サイトに戻る</a>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    public function login()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->index();
            }

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // 簡単な認証（本番では改善が必要）
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_id'] = 1;
                $_SESSION['admin_username'] = $username;
                header('Location: /admin/dashboard');
                exit;
            } else {
                return '<h1>ログインエラー</h1><p>ユーザー名またはパスワードが正しくありません。</p><p><a href="/admin">戻る</a></p>';
            }

        } catch (Exception $e) {
            return '<h1>Login Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /admin');
        exit;
    }
}