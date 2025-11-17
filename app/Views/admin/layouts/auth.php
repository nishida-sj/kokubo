<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? h($title) : '管理画面 - ' . APP_NAME ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/admin.css') ?>">

    <!-- Favicon -->
    <link rel="icon" href="<?= asset_url('img/favicon.ico') ?>" type="image/x-icon">

    <!-- CSRF Token -->
    <?= Csrf::meta() ?>
</head>
<body class="<?= isset($bodyClass) ? h($bodyClass) : 'admin-auth' ?>">
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-header">
                <h1 class="auth-title"><?= h(APP_NAME) ?></h1>
                <p class="auth-subtitle">管理画面</p>
            </div>

            <div class="auth-content">
                <?php
                // ページコンテンツをインクルード
                $pageFile = null;
                if (isset($page) && !empty($page)) {
                    $pageFile = APP_PATH . '/Views/' . $page . '.php';
                }

                if ($pageFile && file_exists($pageFile)) {
                    include $pageFile;
                } else {
                    echo '<div class="error">ページが見つかりません</div>';
                }
                ?>
            </div>

            <div class="auth-footer">
                <p>&copy; <?= date('Y') ?> <?= h(APP_NAME) ?>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= asset_url('js/admin.js') ?>"></script>
</body>
</html>