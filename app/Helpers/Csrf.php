<?php
// CSRF対策ヘルパー

class Csrf
{
    public static function generateToken()
    {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    public static function getToken()
    {
        return $_SESSION[CSRF_TOKEN_NAME] ?? '';
    }

    public static function validateToken($token)
    {
        if (!isset($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    public static function requireToken()
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';

        if (!self::validateToken($token)) {
            throw new Exception('不正なリクエストです。');
        }
    }

    public static function field($name = null)
    {
        $fieldName = $name ?: CSRF_TOKEN_NAME;
        $token = self::generateToken();
        return "<input type=\"hidden\" name=\"{$fieldName}\" value=\"{$token}\">";
    }

    public static function meta()
    {
        $token = self::generateToken();
        return "<meta name=\"csrf-token\" content=\"{$token}\">";
    }

    public static function refreshToken()
    {
        unset($_SESSION[CSRF_TOKEN_NAME]);
        return self::generateToken();
    }
}