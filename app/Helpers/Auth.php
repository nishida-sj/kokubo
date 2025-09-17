<?php
// 認証ヘルパー

class Auth
{
    public static function login($username, $password)
    {
        $db = Db::getInstance();

        $admin = $db->fetch(
            "SELECT * FROM admins WHERE (username = :username OR email = :username) AND is_active = 1",
            ['username' => $username]
        );

        if (!$admin || !password_verify($password, $admin['password'])) {
            return false;
        }

        // ログイン成功
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_username'] = $admin['username'];

        // 最終ログイン時刻を更新
        $db->update('admins', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $admin['id']]);

        return true;
    }

    public static function logout()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_username']);

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            redirect('admin');
            exit;
        }
    }

    public static function getCurrentAdmin()
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        $db = Db::getInstance();
        return $db->fetch("SELECT * FROM admins WHERE id = :id", ['id' => $_SESSION['admin_id']]);
    }

    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function generatePassword($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }

    public static function createAdmin($username, $email, $password, $name)
    {
        $db = Db::getInstance();

        // 重複チェック
        if ($db->exists('admins', 'username = :username OR email = :email', ['username' => $username, 'email' => $email])) {
            throw new Exception('ユーザー名またはメールアドレスが既に使用されています。');
        }

        return $db->insert('admins', [
            'username' => $username,
            'email' => $email,
            'password' => self::hashPassword($password),
            'name' => $name,
            'is_active' => 1
        ]);
    }

    public static function updateAdmin($id, $data)
    {
        $db = Db::getInstance();

        // パスワードがある場合はハッシュ化
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = self::hashPassword($data['password']);
        } else {
            unset($data['password']);
        }

        return $db->update('admins', $data, 'id = :id', ['id' => $id]);
    }
}