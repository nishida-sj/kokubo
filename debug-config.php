<?php
// デバッグ用設定ファイル - エラー詳細を確認するため

// エラー表示を有効化（デバッグ用）
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// 文字エンコーディング設定
mb_language('Japanese');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Tokyo');

// 定数定義（修正版）
define('APP_NAME', '小久保植樹園');
define('APP_VERSION', '1.0.0');
define('BASE_PATH', __DIR__); // dirname(__DIR__) から __DIR__ に変更
define('PUBLIC_PATH', BASE_PATH); // publicディレクトリがない構成用
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('CACHE_PATH', STORAGE_PATH . '/cache');

// URL設定
define('SITE_URL', 'http://kokubosyokuju.geo.jp');
define('ASSET_URL', SITE_URL . '/assets');
define('UPLOAD_URL', SITE_URL . '/uploads');

// データベース設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'nishidasj_kokubo');
define('DB_USER', 'nishidasj');
define('DB_PASS', 'Nqxx');
define('DB_CHARSET', 'utf8mb4');

// セッション設定
define('SESSION_NAME', 'kokubo_session');
define('SESSION_LIFETIME', 3600 * 24);

// セキュリティ設定
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_SALT', 'kokubo_production_salt_2024');

// ファイルアップロード設定
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('THUMBNAIL_WIDTH', 300);
define('THUMBNAIL_HEIGHT', 200);

// ページネーション設定
define('WORKS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// メール設定
define('MAIL_FROM', 'noreply@kokubosyokuju.geo.jp');
define('MAIL_FROM_NAME', '小久保植樹園');
define('MAIL_TO', 'info@kokubosyokuju.geo.jp');
define('MAIL_USE_PHPMAILER', false);

// SEO設定
define('DEFAULT_META_TITLE', '小久保植樹園 | 伊勢市の植樹・造園専門業者');
define('DEFAULT_META_DESCRIPTION', '伊勢市の植樹園。植栽工事・庭園設計・樹木管理を手がける地域密着の造園業者です。緑豊かな空間づくりをお手伝いします。');
define('DEFAULT_META_KEYWORDS', '伊勢市,植樹園,造園,植栽,庭木,剪定,小久保植樹園');
define('OG_IMAGE', ASSET_URL . '/img/og-image.jpg');

// キャッシュ設定
define('CACHE_ENABLED', false); // 一時的に無効化
define('CACHE_LIFETIME', 3600);

// デバッグ設定
define('DEBUG_MODE', true); // デバッグモード有効

// 自動読み込み（修正版）
spl_autoload_register(function ($className) {
    // クラス名のパス変換を簡素化
    $classFile = str_replace('\\', '/', $className) . '.php';
    $classPath = APP_PATH . '/' . $classFile;

    if (file_exists($classPath)) {
        require_once $classPath;
        return true;
    }

    // デバッグ用：読み込み失敗をログ出力
    error_log("Class file not found: $classPath");
    return false;
});

// セッション開始（修正版）
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params(SESSION_LIFETIME);
    if (!session_start()) {
        error_log("Failed to start session");
    }
}

// CSRFトークン生成
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// ヘルパー関数
function site_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset_url($path = '') {
    return ASSET_URL . '/' . ltrim($path, '/');
}

function upload_url($path = '') {
    return UPLOAD_URL . '/' . ltrim($path, '/');
}

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    return $_SESSION[CSRF_TOKEN_NAME] ?? '';
}

function verify_csrf_token($token) {
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

function redirect($url, $statusCode = 302) {
    if (strpos($url, 'http') !== 0) {
        $url = site_url($url);
    }
    header("Location: {$url}", true, $statusCode);
    exit;
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function current_admin_id() {
    return $_SESSION['admin_id'] ?? null;
}

function format_date($date, $format = 'Y年n月j日') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

function truncate_text($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

function generate_slug($text) {
    $text = mb_strtolower($text);
    $text = preg_replace('/[^a-z0-9\-_]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

echo "<!-- Debug: Config loaded successfully -->\n";
?>