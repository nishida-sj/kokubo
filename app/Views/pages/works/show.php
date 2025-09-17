<?php
// 施工実績詳細ページ
?>

<!-- パンくずリスト -->
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb" aria-label="パンくずリスト">
            <ol class="breadcrumb__list">
                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                    <li class="breadcrumb__item">
                        <?php if (isset($breadcrumb['url'])): ?>
                            <a href="<?= site_url($breadcrumb['url']) ?>" class="breadcrumb__link">
                                <?= h($breadcrumb['name']) ?>
                            </a>
                        <?php else: ?>
                            <span class="breadcrumb__current"><?= h($breadcrumb['name']) ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
    </div>
</section>

<!-- 実績詳細メイン -->
<article class="work-detail">
    <div class="container">
        <!-- ヘッダー情報 -->
        <header class="work-detail__header">
            <div class="work-detail__meta">
                <span class="work-detail__category"><?= h($work['category_name']) ?></span>
                <?php if ($work['is_featured']): ?>
                    <span class="work-detail__featured">おすすめ</span>
                <?php endif; ?>
                <time class="work-detail__date" datetime="<?= date('Y-m-d', strtotime($work['created_at'])) ?>">
                    <?= format_date($work['created_at'], 'Y年n月j日') ?>
                </time>
            </div>

            <h1 class="work-detail__title"><?= h($work['title']) ?></h1>

            <?php if (!empty($work['description'])): ?>
                <p class="work-detail__description">
                    <?= nl2br(h($work['description'])) ?>
                </p>
            <?php endif; ?>

            <!-- 基本情報 -->
            <dl class="work-detail__specs">
                <?php if (!empty($work['location'])): ?>
                    <div class="work-detail__spec">
                        <dt class="work-detail__spec-label">所在地</dt>
                        <dd class="work-detail__spec-value"><?= h($work['location']) ?></dd>
                    </div>
                <?php endif; ?>

                <?php if (!empty($work['construction_period'])): ?>
                    <div class="work-detail__spec">
                        <dt class="work-detail__spec-label">工期</dt>
                        <dd class="work-detail__spec-value"><?= h($work['construction_period']) ?></dd>
                    </div>
                <?php endif; ?>

                <?php if (!empty($work['floor_area'])): ?>
                    <div class="work-detail__spec">
                        <dt class="work-detail__spec-label">延床面積</dt>
                        <dd class="work-detail__spec-value"><?= h($work['floor_area']) ?></dd>
                    </div>
                <?php endif; ?>

                <?php if (!empty($work['structure'])): ?>
                    <div class="work-detail__spec">
                        <dt class="work-detail__spec-label">構造</dt>
                        <dd class="work-detail__spec-value"><?= h($work['structure']) ?></dd>
                    </div>
                <?php endif; ?>
            </dl>

            <!-- タグ -->
            <?php if (!empty($workTags)): ?>
                <div class="work-detail__tags">
                    <h3 class="work-detail__tags-title">タグ</h3>
                    <div class="work-detail__tag-list">
                        <?php foreach ($workTags as $tag): ?>
                            <a href="<?= site_url('works?tag=' . $tag['slug']) ?>" class="work-detail__tag">
                                <?= h($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </header>

        <!-- メイン画像 -->
        <?php if (!empty($work['main_image'])): ?>
            <div class="work-detail__main-image">
                <img src="<?= site_url($work['main_image']) ?>"
                     alt="<?= h($work['title']) ?>"
                     class="work-detail__main-img">
            </div>
        <?php endif; ?>

        <!-- 詳細内容 -->
        <?php if (!empty($work['content'])): ?>
            <div class="work-detail__content">
                <div class="work-detail__content-inner">
                    <?= nl2br(h($work['content'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 画像ギャラリー -->
        <?php if (!empty($images)): ?>
            <section class="work-detail__gallery">
                <h2 class="work-detail__gallery-title">施工写真</h2>

                <!-- Before/After画像 -->
                <?php
                $beforeImages = array_filter($images, function($img) { return $img['is_before']; });
                $afterImages = array_filter($images, function($img) { return !$img['is_before']; });
                ?>

                <?php if (!empty($beforeImages)): ?>
                    <div class="work-detail__before-after">
                        <h3 class="work-detail__section-title">Before / After</h3>
                        <div class="before-after-grid">
                            <?php if (!empty($beforeImages)): ?>
                                <div class="before-after__section">
                                    <h4 class="before-after__label">Before</h4>
                                    <div class="before-after__images">
                                        <?php foreach ($beforeImages as $image): ?>
                                            <div class="gallery-item">
                                                <img src="<?= site_url($image['path']) ?>"
                                                     alt="<?= h($image['alt'] ?: $work['title'] . ' Before') ?>"
                                                     class="gallery-item__img"
                                                     loading="lazy">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($afterImages)): ?>
                                <div class="before-after__section">
                                    <h4 class="before-after__label">After</h4>
                                    <div class="before-after__images">
                                        <?php foreach (array_slice($afterImages, 0, count($beforeImages)) as $image): ?>
                                            <div class="gallery-item">
                                                <img src="<?= site_url($image['path']) ?>"
                                                     alt="<?= h($image['alt'] ?: $work['title']) ?>"
                                                     class="gallery-item__img"
                                                     loading="lazy">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- その他の画像 -->
                <?php
                $otherImages = !empty($beforeImages) ? array_slice($afterImages, count($beforeImages)) : $afterImages;
                if (empty($beforeImages)) {
                    $otherImages = $afterImages;
                }
                ?>

                <?php if (!empty($otherImages)): ?>
                    <div class="work-detail__other-images">
                        <?php if (!empty($beforeImages)): ?>
                            <h3 class="work-detail__section-title">その他の写真</h3>
                        <?php endif; ?>
                        <div class="gallery-grid">
                            <?php foreach ($otherImages as $image): ?>
                                <div class="gallery-item" data-animation="fadeInUp">
                                    <img src="<?= site_url($image['path_thumb'] ?: $image['path']) ?>"
                                         alt="<?= h($image['alt'] ?: $work['title']) ?>"
                                         class="gallery-item__img"
                                         data-full-src="<?= site_url($image['path']) ?>"
                                         loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- 関連実績 -->
        <?php if (!empty($relatedWorks)): ?>
            <section class="work-detail__related">
                <h2 class="work-detail__related-title">関連する実績</h2>
                <div class="related-works-grid">
                    <?php foreach ($relatedWorks as $relatedWork): ?>
                        <article class="related-work-card">
                            <a href="<?= site_url('works/' . $relatedWork['slug']) ?>" class="related-work-card__link">
                                <div class="related-work-card__image">
                                    <img src="<?= $relatedWork['main_image'] ? site_url($relatedWork['main_image']) : asset_url('img/no-image.jpg') ?>"
                                         alt="<?= h($relatedWork['title']) ?>"
                                         class="related-work-card__img"
                                         loading="lazy">
                                </div>
                                <div class="related-work-card__content">
                                    <span class="related-work-card__category"><?= h($relatedWork['category_name']) ?></span>
                                    <h3 class="related-work-card__title"><?= h($relatedWork['title']) ?></h3>
                                    <p class="related-work-card__description">
                                        <?= h(truncate_text($relatedWork['description'], 60)) ?>
                                    </p>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- 前後のナビゲーション -->
        <?php if ($prevWork || $nextWork): ?>
            <nav class="work-detail__navigation">
                <div class="work-nav">
                    <?php if ($prevWork): ?>
                        <a href="<?= site_url('works/' . $prevWork['slug']) ?>" class="work-nav__link work-nav__link--prev">
                            <span class="work-nav__direction">← 前の実績</span>
                            <span class="work-nav__title"><?= h(truncate_text($prevWork['title'], 30)) ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ($nextWork): ?>
                        <a href="<?= site_url('works/' . $nextWork['slug']) ?>" class="work-nav__link work-nav__link--next">
                            <span class="work-nav__direction">次の実績 →</span>
                            <span class="work-nav__title"><?= h(truncate_text($nextWork['title'], 30)) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>

        <!-- お問い合わせCTA -->
        <section class="work-detail__cta">
            <div class="work-cta">
                <div class="work-cta__content">
                    <h2 class="work-cta__title">このような施工をご希望ですか？</h2>
                    <p class="work-cta__description">
                        お客様のご要望に合わせて、最適なプランをご提案させていただきます。<br>
                        まずはお気軽にご相談ください。
                    </p>
                    <div class="work-cta__actions">
                        <a href="<?= site_url('contact') ?>" class="btn btn--primary btn--large">
                            お見積り依頼
                        </a>
                        <a href="tel:0596-00-0000" class="btn btn--outline btn--large">
                            📞 電話で相談
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>

<style>
/* 施工実績詳細ページ専用スタイル */

/* パンくずセクション */
.breadcrumb-section {
    padding: var(--space-4) 0;
    background-color: var(--c-gray-100);
    border-bottom: 1px solid var(--c-gray-200);
}

.breadcrumb__list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-2);
    font-size: var(--fs-sm);
}

.breadcrumb__item {
    display: flex;
    align-items: center;
}

.breadcrumb__item:not(:last-child)::after {
    content: '>';
    margin-left: var(--space-2);
    color: var(--c-text-muted);
}

.breadcrumb__link {
    color: var(--c-text-light);
    text-decoration: none;
    transition: color var(--transition-base);
}

.breadcrumb__link:hover {
    color: var(--c-primary);
}

.breadcrumb__current {
    color: var(--c-text);
    font-weight: 500;
}

/* 実績詳細 */
.work-detail {
    padding: var(--space-16) 0 var(--space-24);
    background-color: var(--c-white);
}

/* ヘッダー */
.work-detail__header {
    margin-bottom: var(--space-12);
}

.work-detail__meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--space-4);
    margin-bottom: var(--space-6);
    font-size: var(--fs-sm);
}

.work-detail__category {
    background-color: var(--c-secondary);
    color: var(--c-white);
    font-weight: 500;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-base);
}

.work-detail__featured {
    background-color: var(--c-warning);
    color: var(--c-white);
    font-weight: 700;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-base);
}

.work-detail__date {
    color: var(--c-text-muted);
}

.work-detail__title {
    font-size: var(--fs-3xl);
    font-weight: 700;
    color: var(--c-text);
    line-height: 1.3;
    margin-bottom: var(--space-6);
}

@media (min-width: 768px) {
    .work-detail__title {
        font-size: var(--fs-4xl);
    }
}

.work-detail__description {
    font-size: var(--fs-lg);
    color: var(--c-text-light);
    line-height: 1.7;
    margin-bottom: var(--space-8);
}

/* 基本情報 */
.work-detail__specs {
    display: grid;
    gap: var(--space-4);
    padding: var(--space-6);
    background-color: var(--c-bg);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-8);
}

@media (min-width: 768px) {
    .work-detail__specs {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-6);
    }
}

@media (min-width: 1024px) {
    .work-detail__specs {
        grid-template-columns: repeat(4, 1fr);
    }
}

.work-detail__spec {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.work-detail__spec-label {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
    font-weight: 500;
}

.work-detail__spec-value {
    font-size: var(--fs-base);
    color: var(--c-text);
    font-weight: 600;
}

/* タグ */
.work-detail__tags {
    margin-bottom: var(--space-8);
}

.work-detail__tags-title {
    font-size: var(--fs-base);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-3);
}

.work-detail__tag-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-2);
}

.work-detail__tag {
    padding: var(--space-1) var(--space-3);
    background-color: var(--c-gray-100);
    color: var(--c-text-light);
    text-decoration: none;
    border-radius: var(--radius-base);
    font-size: var(--fs-sm);
    transition: all var(--transition-base);
}

.work-detail__tag:hover {
    background-color: var(--c-secondary);
    color: var(--c-white);
}

/* メイン画像 */
.work-detail__main-image {
    margin-bottom: var(--space-12);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.work-detail__main-img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/9;
    object-fit: cover;
}

/* 詳細内容 */
.work-detail__content {
    margin-bottom: var(--space-12);
}

.work-detail__content-inner {
    max-width: 800px;
    margin: 0 auto;
    font-size: var(--fs-lg);
    line-height: 1.8;
    color: var(--c-text);
}

/* ギャラリー */
.work-detail__gallery {
    margin-bottom: var(--space-16);
}

.work-detail__gallery-title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-8);
    text-align: center;
}

.work-detail__section-title {
    font-size: var(--fs-xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-6);
}

/* Before/After */
.work-detail__before-after {
    margin-bottom: var(--space-12);
}

.before-after-grid {
    display: grid;
    gap: var(--space-8);
}

@media (min-width: 1024px) {
    .before-after-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-12);
    }
}

.before-after__section {
    text-align: center;
}

.before-after__label {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
    padding: var(--space-2) var(--space-4);
    background-color: var(--c-primary);
    color: var(--c-white);
    border-radius: var(--radius-base);
    display: inline-block;
}

.before-after__images {
    display: grid;
    gap: var(--space-4);
}

/* ギャラリーグリッド */
.gallery-grid {
    display: grid;
    gap: var(--space-6);
}

@media (min-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-8);
    }
}

@media (min-width: 1024px) {
    .gallery-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.gallery-item {
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
    transition: all var(--transition-base);
    cursor: pointer;
}

.gallery-item:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.gallery-item__img {
    width: 100%;
    height: auto;
    aspect-ratio: 4/3;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.gallery-item:hover .gallery-item__img {
    transform: scale(1.05);
}

/* 関連実績 */
.work-detail__related {
    margin-bottom: var(--space-16);
}

.work-detail__related-title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-8);
    text-align: center;
}

.related-works-grid {
    display: grid;
    gap: var(--space-6);
}

@media (min-width: 768px) {
    .related-works-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-8);
    }
}

@media (min-width: 1024px) {
    .related-works-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.related-work-card {
    background-color: var(--c-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
    transition: all var(--transition-base);
}

.related-work-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-3px);
}

.related-work-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.related-work-card__image {
    aspect-ratio: 4/3;
    overflow: hidden;
}

.related-work-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.related-work-card:hover .related-work-card__img {
    transform: scale(1.05);
}

.related-work-card__content {
    padding: var(--space-4);
}

.related-work-card__category {
    display: inline-block;
    background-color: var(--c-secondary);
    color: var(--c-white);
    font-size: var(--fs-xs);
    font-weight: 500;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-base);
    margin-bottom: var(--space-2);
}

.related-work-card__title {
    font-size: var(--fs-base);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-2);
    line-height: 1.4;
}

.related-work-card__description {
    font-size: var(--fs-sm);
    color: var(--c-text-light);
    line-height: 1.5;
}

/* ナビゲーション */
.work-detail__navigation {
    margin-bottom: var(--space-16);
}

.work-nav {
    display: flex;
    gap: var(--space-6);
}

.work-nav__link {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    padding: var(--space-6);
    background-color: var(--c-bg);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--c-text);
    transition: all var(--transition-base);
    box-shadow: var(--shadow-base);
}

.work-nav__link:hover {
    background-color: var(--c-primary-light);
    color: var(--c-white);
    box-shadow: var(--shadow-md);
}

.work-nav__link--prev {
    text-align: left;
}

.work-nav__link--next {
    text-align: right;
}

.work-nav__direction {
    font-size: var(--fs-sm);
    font-weight: 500;
    opacity: 0.8;
}

.work-nav__title {
    font-size: var(--fs-base);
    font-weight: 700;
}

/* CTA */
.work-detail__cta {
    margin-top: var(--space-16);
}

.work-cta {
    padding: var(--space-16);
    background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-secondary) 100%);
    border-radius: var(--radius-xl);
    text-align: center;
    color: var(--c-white);
}

.work-cta__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.work-cta__description {
    font-size: var(--fs-lg);
    line-height: 1.7;
    margin-bottom: var(--space-8);
    opacity: 0.9;
}

.work-cta__actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    align-items: center;
}

@media (min-width: 640px) {
    .work-cta__actions {
        flex-direction: row;
        justify-content: center;
    }
}

/* レスポンシブ調整 */
@media (max-width: 767px) {
    .work-detail__title {
        font-size: var(--fs-2xl);
    }

    .work-detail__specs {
        grid-template-columns: 1fr;
    }

    .work-nav {
        flex-direction: column;
    }

    .work-cta {
        padding: var(--space-12);
    }

    .work-cta__title {
        font-size: var(--fs-xl);
    }

    .work-cta__description {
        font-size: var(--fs-base);
    }
}
</style>