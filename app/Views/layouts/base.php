<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <?php
    // SEO設定がある場合は使用、なければデフォルト
    if (isset($seo) && $seo instanceof Seo): ?>
        <?= $seo->renderMeta() ?>
    <?php else: ?>
        <title><?= isset($title) ? h($title) . ' | ' . APP_NAME : DEFAULT_META_TITLE ?></title>
        <meta name="description" content="<?= isset($description) ? h($description) : DEFAULT_META_DESCRIPTION ?>">
        <meta name="keywords" content="<?= DEFAULT_META_KEYWORDS ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" href="<?= asset_url('img/favicon.ico') ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= asset_url('img/apple-touch-icon.png') ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/style.css') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <?= Csrf::meta() ?>

    <?php if (isset($additionalHead)): ?>
        <?= $additionalHead ?>
    <?php endif; ?>
</head>
<body <?= isset($bodyClass) ? 'class="' . h($bodyClass) . '"' : '' ?>>
    <!-- Header -->
    <header class="header" id="header">
        <div class="header__container">
            <div class="header__logo">
                <a href="<?= site_url() ?>">
                    <img src="<?= asset_url('img/logo.png') ?>" alt="<?= h(APP_NAME) ?>" class="header__logo-img">
                </a>
            </div>

            <nav class="header__nav" id="nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <a href="<?= site_url() ?>" class="header__nav-link <?= (($_SERVER['REQUEST_URI'] ?? '') === '/') ? 'is-active' : '' ?>">
                            ホーム
                        </a>
                    </li>
                    <li class="header__nav-item">
                        <a href="<?= site_url('works') ?>" class="header__nav-link <?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/works') === 0) ? 'is-active' : '' ?>">
                            施工実績
                        </a>
                    </li>
                    <li class="header__nav-item">
                        <a href="<?= site_url('contact') ?>" class="header__nav-link <?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/contact') === 0) ? 'is-active' : '' ?>">
                            お問い合わせ
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="header__cta">
                <a href="tel:0596-00-0000" class="header__tel">
                    <span class="header__tel-icon">📞</span>
                    <span class="header__tel-number">0596-00-0000</span>
                </a>
                <a href="<?= site_url('contact') ?>" class="header__btn btn btn--primary">
                    お見積り依頼
                </a>
            </div>

            <button class="header__menu-btn" id="menuBtn" type="button">
                <span class="header__menu-line"></span>
                <span class="header__menu-line"></span>
                <span class="header__menu-line"></span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <?php
        // ページコンテンツをインクルード
        $pageFile = null;
        if (isset($page) && !empty($page)) {
            $pageFile = APP_PATH . '/Views/pages/' . $page . '.php';
        } elseif (isset($contentFile) && !empty($contentFile)) {
            $pageFile = $contentFile;
        }

        if ($pageFile && file_exists($pageFile)) {
            include $pageFile;
        } else {
            echo '<section class="error"><div class="container"><h1>ページが見つかりません</h1></div></section>';
        }
        ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer__container">
            <div class="footer__top">
                <div class="footer__logo">
                    <img src="<?= asset_url('img/logo-white.png') ?>" alt="<?= h(APP_NAME) ?>" class="footer__logo-img">
                </div>

                <div class="footer__info">
                    <div class="footer__company">
                        <h3 class="footer__company-name"><?= h(APP_NAME) ?></h3>
                        <p class="footer__company-address">
                            〒516-0000<br>
                            三重県伊勢市○○町○○番地
                        </p>
                    </div>

                    <div class="footer__contact">
                        <div class="footer__tel">
                            <span class="footer__tel-label">TEL</span>
                            <a href="tel:0596-00-0000" class="footer__tel-number">0596-00-0000</a>
                        </div>
                        <div class="footer__hours">
                            <span class="footer__hours-label">営業時間</span>
                            <span class="footer__hours-text">平日 8:00-18:00 / 土曜 8:00-17:00</span>
                        </div>
                    </div>
                </div>

                <nav class="footer__nav">
                    <ul class="footer__nav-list">
                        <li class="footer__nav-item">
                            <a href="<?= site_url() ?>" class="footer__nav-link">ホーム</a>
                        </li>
                        <li class="footer__nav-item">
                            <a href="<?= site_url('works') ?>" class="footer__nav-link">施工実績</a>
                        </li>
                        <li class="footer__nav-item">
                            <a href="<?= site_url('contact') ?>" class="footer__nav-link">お問い合わせ</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="footer__bottom">
                <p class="footer__copyright">
                    &copy; <?= date('Y') ?> <?= h(APP_NAME) ?>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Sticky CTA (Mobile) -->
    <div class="sticky-cta" id="stickyCta">
        <a href="tel:0596-00-0000" class="sticky-cta__tel">
            <span class="sticky-cta__icon">📞</span>
            <span class="sticky-cta__text">電話</span>
        </a>
        <a href="<?= site_url('contact') ?>" class="sticky-cta__contact">
            <span class="sticky-cta__icon">✉</span>
            <span class="sticky-cta__text">見積り</span>
        </a>
    </div>

    <!-- JavaScript -->
    <script src="<?= asset_url('js/script.js') ?>"></script>

    <?php
    // 構造化データ
    if (isset($schema) && !empty($schema)): ?>
        <?= $schema ?>
    <?php endif; ?>

    <?php if (isset($additionalScripts)): ?>
        <?= $additionalScripts ?>
    <?php endif; ?>
</body>
</html>