<?php
// 小久保植樹園 サブドメイン用設定

// エラーレポート設定（デバッグ用に一時的に有効化）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 文字エンコーディング設定
mb_language('Japanese');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Tokyo');

// 定数定義
define('APP_NAME', '小久保植樹園');
define('APP_VERSION', '1.0.0');
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH); // サブドメインデプロイでは全ファイルがルートに配置される
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOAD_PATH', BASE_PATH . '/uploads'); // PUBLIC_PATH . '/uploads' から変更
define('CACHE_PATH', STORAGE_PATH . '/cache');

// URL設定（サブドメイン用）
define('SITE_URL', 'https://kokubosyokuju.geo.jp');
define('ASSET_URL', SITE_URL . '/assets');
define('UPLOAD_URL', SITE_URL . '/uploads');

// データベース設定（本番環境用）
define('DB_HOST', 'localhost');
define('DB_NAME', 'nishidasj_kokubo'); // 実際のDB名に変更
define('DB_USER', 'nishidasj_kokubo'); // 実際のDBユーザーに変更
define('DB_PASS', 'NishidaSJ'); // 実際のDBパスワードに変更
define('DB_CHARSET', 'utf8mb4');

// セッション設定
define('SESSION_NAME', 'kokubo_session');
define('SESSION_LIFETIME', 3600 * 24); // 24時間

// セキュリティ設定
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_SALT', 'kokubo_production_salt_2024'); // 本番用に複雑な文字列に変更

// ファイルアップロード設定
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
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
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1時間

// デバッグ設定（エラー調査のため一時的に有効化）
define('DEBUG_MODE', true);

// 自動読み込み
spl_autoload_register(function ($className) {
    // 名前空間を含むクラス名の場合
    $classPath = APP_PATH . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($classPath)) {
        require_once $classPath;
        return;
    }

    // Helpers内のクラスを探す
    $helperPath = APP_PATH . '/Helpers/' . $className . '.php';
    if (file_exists($helperPath)) {
        require_once $helperPath;
        return;
    }

    // Controllers内のクラスを探す
    $controllerPath = APP_PATH . '/Controllers/' . $className . '.php';
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        return;
    }
});

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params(SESSION_LIFETIME);
    session_start();
}

// CSRFトークン生成
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// ヘルパー関数（同じ内容）
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
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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
    // まず英数字とハイフンのみに変換を試みる
    $slug = mb_strtolower($text);
    $slug = preg_replace('/[^a-z0-9\-_]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    // スラッグが空または短すぎる場合（日本語タイトルの場合など）
    // タイムスタンプベースのスラッグを生成
    if (strlen($slug) < 3) {
        $slug = 'work-' . date('YmdHis') . '-' . substr(md5($text), 0, 6);
    }

    return $slug;
}

function create_thumbnail($sourcePath, $destPath, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT) {
    if (!file_exists($sourcePath)) {
        return false;
    }

    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }

    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // ソース画像を読み込み
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }

    // サムネイル作成
    $thumbnail = imagecreatetruecolor($width, $height);

    // 透明度を保持（PNG、GIF用）
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
        $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
        imagefill($thumbnail, 0, 0, $transparent);
    }

    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

    // 保存
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($thumbnail, $destPath, 85);
            break;
        case 'image/png':
            $result = imagepng($thumbnail, $destPath);
            break;
        case 'image/gif':
            $result = imagegif($thumbnail, $destPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($thumbnail);

    return $result;
}