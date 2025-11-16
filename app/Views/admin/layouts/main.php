<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? h($title) . ' - ' : '' ?>管理画面 - <?= h(APP_NAME) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/admin.css') ?>">

    <!-- Favicon -->
    <link rel="icon" href="<?= asset_url('img/favicon.ico') ?>" type="image/x-icon">

    <!-- CSRF Token -->
    <?= Csrf::meta() ?>
</head>
<body class="admin-layout">
    <!-- サイドバー -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar__header">
            <img src="<?= asset_url('img/logo-white.png') ?>" alt="<?= h(APP_NAME) ?>" class="admin-sidebar__logo">
            <h1 class="admin-sidebar__title">管理画面</h1>
        </div>

        <nav class="admin-sidebar__nav">
            <ul class="admin-nav__list">
                <li class="admin-nav__item">
                    <a href="<?= site_url('admin/dashboard') ?>"
                       class="admin-nav__link <?= (isset($page) && $page === 'admin/dashboard') ? 'is-active' : '' ?>">
                        <span class="admin-nav__icon">📊</span>
                        ダッシュボード
                    </a>
                </li>
                <li class="admin-nav__item">
                    <a href="<?= site_url('admin/works') ?>"
                       class="admin-nav__link <?= (isset($page) && strpos($page, 'admin/works') === 0) ? 'is-active' : '' ?>">
                        <span class="admin-nav__icon">🏠</span>
                        施工実績管理
                    </a>
                </li>
                <li class="admin-nav__item">
                    <a href="<?= site_url('admin/contacts') ?>"
                       class="admin-nav__link <?= (isset($page) && strpos($page, 'admin/contacts') === 0) ? 'is-active' : '' ?>">
                        <span class="admin-nav__icon">✉</span>
                        お問い合わせ管理
                        <?php
                        $db = Db::getInstance();
                        $unreadCount = $db->count('contacts', 'is_read = 0');
                        if ($unreadCount > 0):
                        ?>
                            <span class="badge badge--error"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="admin-nav__item">
                    <a href="<?= site_url('admin/settings') ?>"
                       class="admin-nav__link <?= (isset($page) && strpos($page, 'admin/settings') === 0) ? 'is-active' : '' ?>">
                        <span class="admin-nav__icon">⚙</span>
                        サイト設定
                    </a>
                </li>
            </ul>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="<?= site_url() ?>" target="_blank" class="admin-nav__link">
                <span class="admin-nav__icon">🌐</span>
                サイトを表示
            </a>
        </div>
    </aside>

    <!-- メインコンテンツ -->
    <main class="admin-main">
        <!-- ヘッダー -->
        <header class="admin-header">
            <div class="admin-header__left">
                <button class="menu-toggle" type="button">☰</button>
                <h2 class="admin-header__title"><?= h($title ?? 'ページ') ?></h2>
            </div>

            <div class="admin-header__right">
                <div class="admin-header__user">
                    <span class="admin-header__username">
                        <?= h($_SESSION['admin_name'] ?? 'ユーザー') ?>さん
                    </span>
                    <a href="<?= site_url('admin/logout') ?>" class="admin-header__logout">
                        ログアウト
                    </a>
                </div>
            </div>
        </header>

        <!-- コンテンツ -->
        <div class="admin-content">
            <?php
            // ページコンテンツをインクルード
            $pageFile = null;
            if (isset($page) && !empty($page)) {
                $pageFile = APP_PATH . '/Views/' . $page . '.php';
            }

            if ($pageFile && file_exists($pageFile)) {
                include $pageFile;
            } else {
                echo '<div class="alert alert--error">ページが見つかりません</div>';
            }
            ?>
        </div>
    </main>

    <!-- JavaScript -->
    <script src="<?= asset_url('js/admin.js') ?>?v=<?= time() ?>"></script>

    <?php if (isset($additionalScripts)): ?>
        <?= $additionalScripts ?>
    <?php endif; ?>
</body>
</html>