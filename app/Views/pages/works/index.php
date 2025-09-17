<?php
// 施工実績一覧ページ
?>

<!-- ページヘッダー -->
<section class="page-header">
    <div class="page-header__background">
        <img src="<?= asset_url('img/works-header-bg.jpg') ?>" alt="施工実績" class="page-header__bg-img">
        <div class="page-header__overlay"></div>
    </div>

    <div class="container">
        <div class="page-header__content">
            <h1 class="page-header__title">
                <?php if ($selectedCategory): ?>
                    <?= h($selectedCategory['name']) ?>の実績
                <?php elseif ($selectedTag): ?>
                    <?= h($selectedTag['name']) ?>の実績
                <?php elseif (!empty($searchQuery)): ?>
                    "<?= h($searchQuery) ?>"の検索結果
                <?php else: ?>
                    施工実績
                <?php endif; ?>
            </h1>

            <p class="page-header__description">
                <?php if ($selectedCategory): ?>
                    <?= h($selectedCategory['name']) ?>の施工実績をご紹介します。
                <?php elseif (!empty($searchQuery)): ?>
                    <?= $pagination['total'] ?>件の実績が見つかりました。
                <?php else: ?>
                    小久保工務店がこれまでに手がけた施工実績をご紹介します。
                <?php endif; ?>
            </p>

            <!-- パンくずリスト -->
            <nav class="breadcrumb" aria-label="パンくずリスト">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item">
                        <a href="<?= site_url() ?>" class="breadcrumb__link">ホーム</a>
                    </li>
                    <?php if ($selectedCategory): ?>
                        <li class="breadcrumb__item">
                            <a href="<?= site_url('works') ?>" class="breadcrumb__link">施工実績</a>
                        </li>
                        <li class="breadcrumb__item breadcrumb__item--current">
                            <?= h($selectedCategory['name']) ?>
                        </li>
                    <?php else: ?>
                        <li class="breadcrumb__item breadcrumb__item--current">
                            施工実績
                        </li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- フィルター・検索 -->
<section class="works-filter">
    <div class="container">
        <div class="works-filter__content">
            <!-- カテゴリーフィルター -->
            <div class="works-filter__categories">
                <h3 class="works-filter__title">カテゴリー</h3>
                <div class="category-filter">
                    <a href="<?= site_url('works') ?>"
                       class="category-filter__item <?= empty($selectedCategory) ? 'is-active' : '' ?>">
                        すべて
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= site_url('works?category=' . $category['slug']) ?>"
                           class="category-filter__item <?= ($selectedCategory && $selectedCategory['slug'] === $category['slug']) ? 'is-active' : '' ?>">
                            <?= h($category['name']) ?>
                            <span class="category-filter__count">(<?= $category['works_count'] ?>)</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 検索フォーム -->
            <div class="works-filter__search">
                <form action="<?= site_url('works') ?>" method="GET" class="search-form">
                    <?php if ($selectedCategory): ?>
                        <input type="hidden" name="category" value="<?= h($selectedCategory['slug']) ?>">
                    <?php endif; ?>

                    <div class="search-form__input-group">
                        <input type="text"
                               name="q"
                               value="<?= h($searchQuery) ?>"
                               placeholder="実績を検索..."
                               class="search-form__input">
                        <button type="submit" class="search-form__button">
                            <span class="search-form__icon">🔍</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- タグフィルター -->
            <?php if (!empty($tags)): ?>
                <div class="works-filter__tags">
                    <h3 class="works-filter__title">タグ</h3>
                    <div class="tag-filter">
                        <?php foreach ($tags as $tag): ?>
                            <a href="<?= site_url('works?tag=' . $tag['slug']) ?>"
                               class="tag-filter__item <?= ($selectedTag && $selectedTag['slug'] === $tag['slug']) ? 'is-active' : '' ?>">
                                <?= h($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 実績一覧 -->
<section class="works-list">
    <div class="container">
        <?php if (!empty($works)): ?>
            <!-- 件数表示 -->
            <div class="works-list__meta">
                <p class="works-list__count">
                    <?= $pagination['total'] ?>件中
                    <?= (($pagination['page'] - 1) * $pagination['perPage'] + 1) ?>-<?= min($pagination['page'] * $pagination['perPage'], $pagination['total']) ?>件を表示
                </p>
            </div>

            <!-- 実績グリッド -->
            <div class="works-grid">
                <?php foreach ($works as $index => $work): ?>
                    <article class="works-card" data-animation="fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                        <a href="<?= site_url('works/' . $work['slug']) ?>" class="works-card__link">
                            <div class="works-card__image">
                                <img src="<?= $work['main_image'] ? site_url($work['main_image']) : asset_url('img/no-image.jpg') ?>"
                                     alt="<?= h($work['title']) ?>"
                                     class="works-card__img"
                                     loading="lazy">

                                <div class="works-card__badges">
                                    <span class="works-card__category"><?= h($work['category_name']) ?></span>
                                    <?php if ($work['is_featured']): ?>
                                        <span class="works-card__featured">おすすめ</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="works-card__content">
                                <h3 class="works-card__title"><?= h($work['title']) ?></h3>

                                <p class="works-card__description">
                                    <?= h(truncate_text($work['description'], 100)) ?>
                                </p>

                                <div class="works-card__meta">
                                    <?php if (!empty($work['location'])): ?>
                                        <span class="works-card__location">
                                            <span class="works-card__meta-icon">📍</span>
                                            <?= h($work['location']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <span class="works-card__date">
                                        <span class="works-card__meta-icon">📅</span>
                                        <?= format_date($work['created_at'], 'Y年n月') ?>
                                    </span>
                                </div>

                                <div class="works-card__specs">
                                    <?php if (!empty($work['floor_area'])): ?>
                                        <span class="works-card__spec">
                                            <span class="works-card__spec-label">延床面積</span>
                                            <span class="works-card__spec-value"><?= h($work['floor_area']) ?></span>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($work['structure'])): ?>
                                        <span class="works-card__spec">
                                            <span class="works-card__spec-label">構造</span>
                                            <span class="works-card__spec-value"><?= h($work['structure']) ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- ページネーション -->
            <?php if ($pagination['totalPages'] > 1): ?>
                <nav class="pagination" aria-label="ページネーション">
                    <div class="pagination__container">
                        <!-- 前のページ -->
                        <?php if ($pagination['hasPrev']): ?>
                            <?php
                            $prevParams = $_GET;
                            $prevParams['page'] = $pagination['page'] - 1;
                            if ($prevParams['page'] == 1) unset($prevParams['page']);
                            ?>
                            <a href="<?= site_url('works?' . http_build_query($prevParams)) ?>"
                               class="pagination__link pagination__link--prev">
                                ← 前のページ
                            </a>
                        <?php endif; ?>

                        <!-- ページ番号 -->
                        <div class="pagination__numbers">
                            <?php
                            $startPage = max(1, $pagination['page'] - 2);
                            $endPage = min($pagination['totalPages'], $pagination['page'] + 2);
                            ?>

                            <?php if ($startPage > 1): ?>
                                <a href="<?= site_url('works?' . http_build_query(array_merge($_GET, ['page' => 1]))) ?>"
                                   class="pagination__number">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="pagination__ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php
                                $pageParams = $_GET;
                                $pageParams['page'] = $i;
                                if ($i == 1) unset($pageParams['page']);
                                ?>
                                <?php if ($i == $pagination['page']): ?>
                                    <span class="pagination__number pagination__number--current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="<?= site_url('works?' . http_build_query($pageParams)) ?>"
                                       class="pagination__number"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($endPage < $pagination['totalPages']): ?>
                                <?php if ($endPage < $pagination['totalPages'] - 1): ?>
                                    <span class="pagination__ellipsis">...</span>
                                <?php endif; ?>
                                <a href="<?= site_url('works?' . http_build_query(array_merge($_GET, ['page' => $pagination['totalPages']]))) ?>"
                                   class="pagination__number"><?= $pagination['totalPages'] ?></a>
                            <?php endif; ?>
                        </div>

                        <!-- 次のページ -->
                        <?php if ($pagination['hasNext']): ?>
                            <?php
                            $nextParams = $_GET;
                            $nextParams['page'] = $pagination['page'] + 1;
                            ?>
                            <a href="<?= site_url('works?' . http_build_query($nextParams)) ?>"
                               class="pagination__link pagination__link--next">
                                次のページ →
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <!-- 実績が見つからない場合 -->
            <div class="works-empty">
                <div class="works-empty__content">
                    <div class="works-empty__icon">🏠</div>
                    <h3 class="works-empty__title">実績が見つかりませんでした</h3>
                    <p class="works-empty__description">
                        <?php if (!empty($searchQuery)): ?>
                            「<?= h($searchQuery) ?>」に該当する実績が見つかりませんでした。<br>
                            別のキーワードでお試しください。
                        <?php elseif ($selectedCategory): ?>
                            「<?= h($selectedCategory['name']) ?>」の実績はまだ登録されていません。
                        <?php else: ?>
                            現在表示できる実績がありません。
                        <?php endif; ?>
                    </p>
                    <div class="works-empty__actions">
                        <a href="<?= site_url('works') ?>" class="btn btn--outline">すべての実績を見る</a>
                        <a href="<?= site_url('contact') ?>" class="btn btn--primary">お問い合わせ</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* 施工実績一覧ページ専用スタイル */

/* ページヘッダー */
.page-header {
    position: relative;
    padding: var(--space-24) 0 var(--space-16);
    background-color: var(--c-gray-800);
    color: var(--c-white);
    overflow: hidden;
}

.page-header__background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.page-header__bg-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.3;
}

.page-header__overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.8) 0%, rgba(47, 82, 51, 0.6) 100%);
}

.page-header__content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.page-header__title {
    font-size: var(--fs-4xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
    line-height: 1.2;
}

@media (min-width: 768px) {
    .page-header__title {
        font-size: var(--fs-5xl);
    }
}

.page-header__description {
    font-size: var(--fs-lg);
    line-height: 1.7;
    margin-bottom: var(--space-8);
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* パンくずリスト */
.breadcrumb {
    margin-top: var(--space-8);
}

.breadcrumb__list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
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
    opacity: 0.6;
}

.breadcrumb__link {
    color: var(--c-white);
    text-decoration: none;
    opacity: 0.8;
    transition: opacity var(--transition-base);
}

.breadcrumb__link:hover {
    opacity: 1;
}

.breadcrumb__item--current {
    opacity: 1;
    font-weight: 500;
}

/* フィルター */
.works-filter {
    padding: var(--space-16) 0;
    background-color: var(--c-white);
    border-bottom: 1px solid var(--c-gray-200);
}

.works-filter__content {
    display: grid;
    gap: var(--space-8);
}

@media (min-width: 1024px) {
    .works-filter__content {
        grid-template-columns: 1fr auto 1fr;
        align-items: start;
        gap: var(--space-12);
    }
}

.works-filter__title {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
}

/* カテゴリーフィルター */
.category-filter {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
}

.category-filter__item {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2) var(--space-4);
    background-color: var(--c-gray-100);
    color: var(--c-text);
    text-decoration: none;
    border-radius: var(--radius-full);
    font-size: var(--fs-sm);
    font-weight: 500;
    transition: all var(--transition-base);
}

.category-filter__item:hover,
.category-filter__item.is-active {
    background-color: var(--c-primary);
    color: var(--c-white);
}

.category-filter__count {
    font-size: var(--fs-xs);
    opacity: 0.8;
}

/* 検索フォーム */
.search-form__input-group {
    display: flex;
    max-width: 400px;
    margin: 0 auto;
    border: 2px solid var(--c-gray-300);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: border-color var(--transition-base);
}

.search-form__input-group:focus-within {
    border-color: var(--c-primary);
}

.search-form__input {
    flex: 1;
    padding: var(--space-3) var(--space-4);
    border: none;
    font-size: var(--fs-base);
    background: transparent;
}

.search-form__input:focus {
    outline: none;
}

.search-form__button {
    padding: var(--space-3) var(--space-4);
    background-color: var(--c-primary);
    color: var(--c-white);
    border: none;
    cursor: pointer;
    transition: background-color var(--transition-base);
}

.search-form__button:hover {
    background-color: var(--c-primary-dark);
}

.search-form__icon {
    font-size: var(--fs-lg);
}

/* タグフィルター */
.tag-filter {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-2);
}

.tag-filter__item {
    padding: var(--space-1) var(--space-3);
    background-color: var(--c-gray-100);
    color: var(--c-text-light);
    text-decoration: none;
    border-radius: var(--radius-base);
    font-size: var(--fs-xs);
    transition: all var(--transition-base);
}

.tag-filter__item:hover,
.tag-filter__item.is-active {
    background-color: var(--c-secondary);
    color: var(--c-white);
}

/* 実績一覧 */
.works-list {
    padding: var(--space-16) 0 var(--space-24);
    background-color: var(--c-bg);
}

.works-list__meta {
    margin-bottom: var(--space-12);
    text-align: center;
}

.works-list__count {
    font-size: var(--fs-base);
    color: var(--c-text-light);
}

/* 実績グリッド */
.works-grid {
    display: grid;
    gap: var(--space-8);
    margin-bottom: var(--space-16);
}

@media (min-width: 768px) {
    .works-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-12);
    }
}

@media (min-width: 1024px) {
    .works-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* 実績カード */
.works-card {
    background-color: var(--c-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
    transition: all var(--transition-base);
}

.works-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}

.works-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.works-card__image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}

.works-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.works-card:hover .works-card__img {
    transform: scale(1.05);
}

.works-card__badges {
    position: absolute;
    top: var(--space-4);
    left: var(--space-4);
    right: var(--space-4);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.works-card__category {
    background-color: var(--c-secondary);
    color: var(--c-white);
    font-size: var(--fs-xs);
    font-weight: 500;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-base);
}

.works-card__featured {
    background-color: var(--c-warning);
    color: var(--c-white);
    font-size: var(--fs-xs);
    font-weight: 700;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-base);
}

.works-card__content {
    padding: var(--space-6);
}

.works-card__title {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-3);
    line-height: 1.4;
}

.works-card__description {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.6;
    margin-bottom: var(--space-4);
}

.works-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-4);
    margin-bottom: var(--space-4);
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
}

.works-card__location,
.works-card__date {
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.works-card__meta-icon {
    font-size: var(--fs-base);
}

.works-card__specs {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-4);
    font-size: var(--fs-sm);
}

.works-card__spec {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.works-card__spec-label {
    color: var(--c-text-muted);
    font-size: var(--fs-xs);
}

.works-card__spec-value {
    color: var(--c-text);
    font-weight: 500;
}

/* ページネーション */
.pagination {
    margin-top: var(--space-16);
}

.pagination__container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.pagination__link,
.pagination__number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: var(--space-2) var(--space-3);
    text-decoration: none;
    color: var(--c-text);
    border: 1px solid var(--c-gray-300);
    border-radius: var(--radius-base);
    font-size: var(--fs-sm);
    font-weight: 500;
    transition: all var(--transition-base);
}

.pagination__link:hover,
.pagination__number:hover {
    background-color: var(--c-primary);
    color: var(--c-white);
    border-color: var(--c-primary);
}

.pagination__number--current {
    background-color: var(--c-primary);
    color: var(--c-white);
    border-color: var(--c-primary);
}

.pagination__ellipsis {
    padding: var(--space-2);
    color: var(--c-text-muted);
}

.pagination__link--prev,
.pagination__link--next {
    min-width: auto;
    padding: var(--space-2) var(--space-4);
}

/* 空状態 */
.works-empty {
    padding: var(--space-24) 0;
    text-align: center;
}

.works-empty__content {
    max-width: 500px;
    margin: 0 auto;
}

.works-empty__icon {
    font-size: 4rem;
    margin-bottom: var(--space-6);
    opacity: 0.5;
}

.works-empty__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
}

.works-empty__description {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.7;
    margin-bottom: var(--space-8);
}

.works-empty__actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    align-items: center;
}

@media (min-width: 640px) {
    .works-empty__actions {
        flex-direction: row;
        justify-content: center;
    }
}

/* レスポンシブ調整 */
@media (max-width: 767px) {
    .page-header {
        padding: var(--space-16) 0 var(--space-12);
    }

    .page-header__title {
        font-size: var(--fs-3xl);
    }

    .page-header__description {
        font-size: var(--fs-base);
    }

    .works-filter {
        padding: var(--space-12) 0;
    }

    .works-filter__content {
        gap: var(--space-6);
    }

    .breadcrumb__list {
        justify-content: flex-start;
    }
}
</style>