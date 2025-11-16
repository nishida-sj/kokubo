<?php
// 管理画面実績一覧
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">施工実績管理</h1>
        <p class="page-description">実績の追加・編集・削除を行えます</p>
    </div>
    <div class="page-header__right">
        <a href="<?= site_url('admin/works/create') ?>" class="btn btn--primary">
            ＋ 新しい実績を追加
        </a>
    </div>
</div>

<!-- フィルター・検索 -->
<div class="card mb-3">
    <div class="card__content">
        <form action="<?= site_url('admin/works') ?>" method="GET" class="filter-form">
            <div class="filter-form__row">
                <!-- 検索 -->
                <div class="filter-form__group">
                    <label class="form-label">検索</label>
                    <input type="text"
                           name="q"
                           value="<?= h($filters['search']) ?>"
                           placeholder="タイトル、説明、所在地で検索..."
                           class="form-input">
                </div>

                <!-- カテゴリー -->
                <div class="filter-form__group">
                    <label class="form-label">カテゴリー</label>
                    <select name="category" class="form-select">
                        <option value="">すべて</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                    <?= ($filters['category'] == $category['id']) ? 'selected' : '' ?>>
                                <?= h($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ステータス -->
                <div class="filter-form__group">
                    <label class="form-label">ステータス</label>
                    <select name="status" class="form-select">
                        <option value="">すべて</option>
                        <option value="published" <?= ($filters['status'] === 'published') ? 'selected' : '' ?>>公開中</option>
                        <option value="draft" <?= ($filters['status'] === 'draft') ? 'selected' : '' ?>>下書き</option>
                        <option value="featured" <?= ($filters['status'] === 'featured') ? 'selected' : '' ?>>おすすめ</option>
                    </select>
                </div>

                <!-- ソート -->
                <div class="filter-form__group">
                    <label class="form-label">並び順</label>
                    <select name="sort" class="form-select">
                        <option value="created_at" <?= ($filters['sort'] === 'created_at') ? 'selected' : '' ?>>作成日</option>
                        <option value="updated_at" <?= ($filters['sort'] === 'updated_at') ? 'selected' : '' ?>>更新日</option>
                        <option value="title" <?= ($filters['sort'] === 'title') ? 'selected' : '' ?>>タイトル</option>
                        <option value="category_name" <?= ($filters['sort'] === 'category_name') ? 'selected' : '' ?>>カテゴリー</option>
                    </select>
                </div>

                <div class="filter-form__group">
                    <label class="form-label">順序</label>
                    <select name="order" class="form-select">
                        <option value="desc" <?= ($filters['order'] === 'desc') ? 'selected' : '' ?>>降順</option>
                        <option value="asc" <?= ($filters['order'] === 'asc') ? 'selected' : '' ?>>昇順</option>
                    </select>
                </div>

                <!-- 検索ボタン -->
                <div class="filter-form__group">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn--primary">絞り込み</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 実績リスト -->
<div class="card">
    <div class="card__header">
        <h3 class="card__title">
            実績一覧
            <span class="count">(<?= number_format($pagination['total']) ?>件)</span>
        </h3>
        <?php if ($pagination['total'] > 0): ?>
            <div class="card__actions">
                <span class="text-small text-muted">
                    <?= number_format(($pagination['page'] - 1) * $pagination['perPage'] + 1) ?>-<?= number_format(min($pagination['page'] * $pagination['perPage'], $pagination['total'])) ?>件を表示
                </span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($works)): ?>
        <div class="card__content">
            <div class="table-responsive">
                <table class="table table--hover">
                    <thead>
                        <tr>
                            <th width="60">画像</th>
                            <th>タイトル</th>
                            <th width="120">カテゴリー</th>
                            <th width="100">ステータス</th>
                            <th width="100">作成日</th>
                            <th width="120">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($works as $work): ?>
                            <tr>
                                <td>
                                    <div class="work-thumb">
                                        <?php if (!empty($work['main_image'])): ?>
                                            <?php
                                            // 画像パスの自動修正（旧形式のパスに/uploadsを追加）
                                            $imagePath = $work['main_image'];
                                            if (strpos($imagePath, '/uploads/') === false && strpos($imagePath, '/') === 0) {
                                                $imagePath = '/uploads' . $imagePath;
                                            }
                                            ?>
                                            <img src="<?= site_url($imagePath) ?>"
                                                 alt="<?= h($work['title']) ?>"
                                                 class="work-thumb__img">
                                        <?php else: ?>
                                            <div class="work-thumb__placeholder">🏠</div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="work-title">
                                        <h4 class="work-title__text">
                                            <a href="<?= site_url('admin/works/' . $work['id'] . '/edit') ?>"
                                               class="work-title__link">
                                                <?= h($work['title']) ?>
                                            </a>
                                        </h4>
                                        <?php if (!empty($work['location'])): ?>
                                            <div class="work-title__meta">
                                                📍 <?= h($work['location']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($work['description'])): ?>
                                            <div class="work-title__description">
                                                <?= h(truncate_text($work['description'], 60)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        <?= h($work['category_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="status-badges">
                                        <?php if ($work['is_published']): ?>
                                            <span class="status status--published">公開中</span>
                                        <?php else: ?>
                                            <span class="status status--draft">下書き</span>
                                        <?php endif; ?>
                                        <?php if ($work['is_featured']): ?>
                                            <span class="status status--featured">おすすめ</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-small text-muted">
                                    <?= format_date($work['created_at'], 'Y/m/d') ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= site_url('works/' . $work['slug']) ?>"
                                           target="_blank"
                                           class="action-btn action-btn--view"
                                           title="表示">
                                            👁
                                        </a>
                                        <a href="<?= site_url('admin/works/' . $work['id'] . '/edit') ?>"
                                           class="action-btn action-btn--edit"
                                           title="編集">
                                            ✏
                                        </a>
                                        <a href="<?= site_url('admin/works/' . $work['id'] . '/delete') ?>"
                                           class="action-btn action-btn--delete confirm-delete"
                                           title="削除">
                                            🗑
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ページネーション -->
        <?php if ($pagination['totalPages'] > 1): ?>
            <div class="card__footer">
                <nav class="pagination">
                    <?php if ($pagination['hasPrev']): ?>
                        <?php
                        $prevParams = $_GET;
                        $prevParams['page'] = $pagination['page'] - 1;
                        if ($prevParams['page'] == 1) unset($prevParams['page']);
                        ?>
                        <a href="<?= site_url('admin/works?' . http_build_query($prevParams)) ?>"
                           class="pagination__link">前へ</a>
                    <?php endif; ?>

                    <?php
                    $startPage = max(1, $pagination['page'] - 2);
                    $endPage = min($pagination['totalPages'], $pagination['page'] + 2);
                    ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php
                        $pageParams = $_GET;
                        $pageParams['page'] = $i;
                        if ($i == 1) unset($pageParams['page']);
                        ?>
                        <?php if ($i == $pagination['page']): ?>
                            <span class="pagination__link is-active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= site_url('admin/works?' . http_build_query($pageParams)) ?>"
                               class="pagination__link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagination['hasNext']): ?>
                        <?php
                        $nextParams = $_GET;
                        $nextParams['page'] = $pagination['page'] + 1;
                        ?>
                        <a href="<?= site_url('admin/works?' . http_build_query($nextParams)) ?>"
                           class="pagination__link">次へ</a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card__content">
            <div class="empty-state">
                <div class="empty-state__icon">🏠</div>
                <h3 class="empty-state__title">実績がありません</h3>
                <p class="empty-state__text">
                    <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['status'])): ?>
                        検索条件に一致する実績が見つかりませんでした。<br>
                        <a href="<?= site_url('admin/works') ?>" class="link">すべての実績を表示</a>
                    <?php else: ?>
                        まだ実績が追加されていません。<br>
                        最初の実績を追加してみましょう。
                    <?php endif; ?>
                </p>
                <a href="<?= site_url('admin/works/create') ?>" class="btn btn--primary">
                    実績を追加
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* 実績管理一覧ページ専用スタイル */

/* ページヘッダー */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    gap: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--admin-text);
    margin: 0 0 4px 0;
}

.page-description {
    font-size: 14px;
    color: var(--admin-text-light);
    margin: 0;
}

/* フィルターフォーム */
.filter-form__row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 16px;
    align-items: end;
}

@media (max-width: 1024px) {
    .filter-form__row {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
}

@media (max-width: 768px) {
    .filter-form__row {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
}

.filter-form__group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* カウント */
.count {
    font-size: 14px;
    font-weight: 400;
    color: var(--admin-text-light);
}

/* 実績サムネイル */
.work-thumb {
    width: 50px;
    height: 50px;
    border-radius: var(--admin-radius);
    overflow: hidden;
    background-color: var(--admin-bg);
}

.work-thumb__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.work-thumb__placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--admin-text-muted);
}

/* 実績タイトル */
.work-title__text {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.work-title__link {
    color: var(--admin-text);
    text-decoration: none;
}

.work-title__link:hover {
    color: var(--admin-primary);
}

.work-title__meta {
    font-size: 12px;
    color: var(--admin-text-light);
    margin-bottom: 4px;
}

.work-title__description {
    font-size: 12px;
    color: var(--admin-text-muted);
    line-height: 1.4;
}

/* カテゴリーバッジ */
.category-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: var(--admin-info);
    color: var(--admin-white);
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

/* ステータス */
.status-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* アクションボタン */
.action-btn--view {
    background-color: var(--admin-info);
    color: var(--admin-white);
}

.action-btn--view:hover {
    background-color: #1976D2;
}

/* テーブルレスポンシブ */
.table-responsive {
    overflow-x: auto;
}

@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }

    .table th,
    .table td {
        padding: 8px 4px;
    }

    .work-thumb {
        width: 40px;
        height: 40px;
    }

    .actions {
        flex-direction: column;
        gap: 4px;
    }

    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}

/* 空状態 */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state__icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state__title {
    font-size: 20px;
    font-weight: 600;
    color: var(--admin-text);
    margin-bottom: 12px;
}

.empty-state__text {
    font-size: 14px;
    color: var(--admin-text-light);
    line-height: 1.6;
    margin-bottom: 24px;
}

.link {
    color: var(--admin-primary);
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}
</style>