<?php
// 管理画面ダッシュボード
?>

<!-- 統計情報 -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon">📋</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($worksCount) ?></div>
            <div class="stat-card__label">施工実績</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon">📁</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($categoriesCount) ?></div>
            <div class="stat-card__label">カテゴリー</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon">📞</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($contactsCount) ?></div>
            <div class="stat-card__label">お問い合わせ</div>
        </div>
    </div>
</div>

<!-- 管理メニュー -->
<div class="card">
    <div class="card__header">
        <h3 class="card__title">管理メニュー</h3>
    </div>
    <div class="card__content">
        <div class="menu-grid">
            <a href="<?= site_url('admin/works') ?>" class="menu-card">
                <div class="menu-card__icon">📋</div>
                <div class="menu-card__title">実績管理</div>
                <div class="menu-card__description">施工実績の追加・編集</div>
            </a>

            <a href="<?= site_url('admin/contacts') ?>" class="menu-card">
                <div class="menu-card__icon">📞</div>
                <div class="menu-card__title">お問い合わせ管理</div>
                <div class="menu-card__description">お問い合わせ一覧・返信</div>
            </a>

            <a href="<?= site_url('admin/recruit') ?>" class="menu-card">
                <div class="menu-card__icon">🌱</div>
                <div class="menu-card__title">採用情報管理</div>
                <div class="menu-card__description">募集職種・福利厚生編集</div>
            </a>

            <a href="<?= site_url('admin/company') ?>" class="menu-card">
                <div class="menu-card__icon">🏢</div>
                <div class="menu-card__title">会社案内管理</div>
                <div class="menu-card__description">代表挨拶・会社概要編集</div>
            </a>

            <a href="<?= site_url('admin/tags') ?>" class="menu-card">
                <div class="menu-card__icon">🏷️</div>
                <div class="menu-card__title">タグ管理</div>
                <div class="menu-card__description">実績タグの追加・編集</div>
            </a>

            <a href="<?= site_url('admin/categories') ?>" class="menu-card">
                <div class="menu-card__icon">📁</div>
                <div class="menu-card__title">カテゴリー管理</div>
                <div class="menu-card__description">実績カテゴリーの追加・編集</div>
            </a>

            <a href="<?= site_url('admin/settings') ?>" class="menu-card">
                <div class="menu-card__icon">⚙️</div>
                <div class="menu-card__title">サイト設定</div>
                <div class="menu-card__description">基本情報・SEO設定</div>
            </a>

            <a href="<?= site_url() ?>" target="_blank" class="menu-card">
                <div class="menu-card__icon">🌿</div>
                <div class="menu-card__title">サイト表示</div>
                <div class="menu-card__description">公開サイトを確認</div>
            </a>
        </div>
    </div>
</div>

<style>
/* ダッシュボード専用スタイル */

/* 統計カード */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: var(--admin-radius);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-card__icon {
    font-size: 48px;
    opacity: 0.8;
}

.stat-card__number {
    font-size: 36px;
    font-weight: 700;
    color: var(--admin-primary);
    line-height: 1;
    margin-bottom: 8px;
}

.stat-card__label {
    font-size: 14px;
    color: var(--admin-text-light);
}

/* メニューグリッド */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.menu-card {
    background: var(--admin-bg);
    border-radius: var(--admin-radius);
    padding: 20px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.menu-card:hover {
    background: #e8f5e8;
    border-color: var(--admin-primary);
    transform: translateY(-2px);
}

.menu-card__icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.menu-card__title {
    font-size: 16px;
    font-weight: 600;
    color: var(--admin-text);
    margin-bottom: 8px;
}

.menu-card__description {
    font-size: 12px;
    color: var(--admin-text-light);
    line-height: 1.4;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .menu-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-card__icon {
        font-size: 36px;
    }

    .stat-card__number {
        font-size: 28px;
    }
}
</style>
