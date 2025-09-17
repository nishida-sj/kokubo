<?php
// 404エラーページ
?>

<section class="error-page">
    <div class="container">
        <div class="error-page__content">
            <div class="error-page__icon">
                <span class="error-page__number">404</span>
            </div>

            <h1 class="error-page__title">ページが見つかりません</h1>

            <p class="error-page__description">
                申し訳ございません。お探しのページは存在しないか、<br>
                移動または削除された可能性があります。
            </p>

            <div class="error-page__actions">
                <a href="<?= site_url() ?>" class="btn btn--primary btn--large">
                    ホームに戻る
                </a>
                <a href="<?= site_url('works') ?>" class="btn btn--outline btn--large">
                    施工実績を見る
                </a>
            </div>

            <div class="error-page__help">
                <h3 class="error-page__help-title">お困りの際は</h3>
                <p class="error-page__help-text">
                    ご不明な点やお探しの情報が見つからない場合は、<br>
                    お気軽にお問い合わせください。
                </p>
                <div class="error-page__contact">
                    <a href="tel:0596-00-0000" class="error-page__tel">
                        📞 0596-00-0000
                    </a>
                    <a href="<?= site_url('contact') ?>" class="error-page__contact-link">
                        お問い合わせフォーム
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* 404エラーページ専用スタイル */
.error-page {
    padding: var(--space-24) 0;
    background-color: var(--c-bg);
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.error-page__content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.error-page__icon {
    margin-bottom: var(--space-8);
}

.error-page__number {
    font-size: 8rem;
    font-weight: 700;
    color: var(--c-primary);
    line-height: 1;
    display: block;
}

@media (min-width: 768px) {
    .error-page__number {
        font-size: 10rem;
    }
}

.error-page__title {
    font-size: var(--fs-3xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-6);
}

@media (min-width: 768px) {
    .error-page__title {
        font-size: var(--fs-4xl);
    }
}

.error-page__description {
    font-size: var(--fs-lg);
    color: var(--c-text-light);
    line-height: 1.7;
    margin-bottom: var(--space-12);
}

.error-page__actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    align-items: center;
    margin-bottom: var(--space-16);
}

@media (min-width: 640px) {
    .error-page__actions {
        flex-direction: row;
        justify-content: center;
    }
}

.error-page__help {
    padding: var(--space-8);
    background-color: var(--c-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-base);
}

.error-page__help-title {
    font-size: var(--fs-xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
}

.error-page__help-text {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.7;
    margin-bottom: var(--space-6);
}

.error-page__contact {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    align-items: center;
}

@media (min-width: 640px) {
    .error-page__contact {
        flex-direction: row;
        justify-content: center;
    }
}

.error-page__tel {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-primary);
    text-decoration: none;
    transition: color var(--transition-base);
}

.error-page__tel:hover {
    color: var(--c-primary-dark);
}

.error-page__contact-link {
    color: var(--c-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition-base);
}

.error-page__contact-link:hover {
    color: var(--c-secondary-light);
}
</style>