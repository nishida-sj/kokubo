<?php
// POSTテスト用デバッグページ
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>POST Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .ok { color: green; }
        .error { color: red; }
        pre { background: white; padding: 15px; border-left: 4px solid #2E7D32; }
    </style>
</head>
<body>
    <h1>POST Request Test</h1>

    <h2>1. Simple Form Test</h2>
    <form action="test-post.php" method="POST">
        <input type="text" name="test_field" value="test value" />
        <button type="submit">Test POST</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3 class="ok">✅ POST Request Received!</h3>
        <pre><?php print_r($_POST); ?></pre>
    <?php endif; ?>

    <h2>2. Admin Route Test</h2>
    <p>Current Request URI: <code><?= $_SERVER['REQUEST_URI'] ?></code></p>
    <p>Request Method: <code><?= $_SERVER['REQUEST_METHOD'] ?></code></p>

    <h2>3. Config Check</h2>
    <pre><?php
    $configPath = __DIR__ . '/config/config.php';
    echo "Config file exists: " . (file_exists($configPath) ? "✅ Yes" : "❌ No") . "\n";

    if (file_exists($configPath)) {
        require_once $configPath;
        echo "SITE_URL: " . (defined('SITE_URL') ? SITE_URL : 'Not defined') . "\n";
        echo "DEBUG_MODE: " . (defined('DEBUG_MODE') ? (DEBUG_MODE ? 'true' : 'false') : 'Not defined') . "\n";
    }
    ?></pre>

    <h2>4. .htaccess Check</h2>
    <pre><?php
    $htaccessPath = __DIR__ . '/.htaccess';
    if (file_exists($htaccessPath)) {
        echo "✅ .htaccess exists\n\n";
        echo "Content:\n";
        echo htmlspecialchars(file_get_contents($htaccessPath));
    } else {
        echo "❌ .htaccess not found";
    }
    ?></pre>

    <h2>5. Router Test</h2>
    <form action="/admin/works/store" method="POST">
        <p>This form will POST to: <code>/admin/works/store</code></p>
        <input type="text" name="title" value="Test Title" required />
        <button type="submit">Test Admin POST</button>
    </form>

</body>
</html>
