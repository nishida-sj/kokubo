<?php
// タグ管理一覧
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">タグ管理</h1>
        <p class="page-description">実績タグの追加・編集・削除を行えます</p>
    </div>
    <div class="page-header__right">
        <a href="<?= site_url('admin/tags/create') ?>" class="btn btn--primary">
            + 新しいタグを追加
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert--success mb-3">
        <?= h($successMessage) ?>
    </div>
<?php endif; ?>

<!-- タグリスト -->
<div class="card">
    <div class="card__header">
        <h3 class="card__title">
            タグ一覧
            <span class="count">(<?= count($tags) ?>件)</span>
        </h3>
    </div>

    <?php if (!empty($tags)): ?>
        <div class="card__content">
            <div class="table-responsive">
                <table class="table table--hover">
                    <thead>
                        <tr>
                            <?php if ($hasDisplayOrder): ?>
                                <th width="80">表示順</th>
                            <?php endif; ?>
                            <th>タグ名</th>
                            <?php if ($hasDisplayOrder): ?>
                                <th width="150">作成日</th>
                            <?php endif; ?>
                            <th width="200">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags as $tag): ?>
                            <tr>
                                <?php if ($hasDisplayOrder): ?>
                                    <td><?= h($tag['display_order'] ?? 0) ?></td>
                                <?php endif; ?>
                                <td><?= h($tag['name']) ?></td>
                                <?php if ($hasDisplayOrder): ?>
                                    <td class="text-small text-muted">
                                        <?= date('Y/m/d', strtotime($tag['created_at'])) ?>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <div class="actions">
                                        <a href="<?= site_url('admin/tags/' . $tag['id'] . '/edit') ?>"
                                           class="action-btn action-btn--edit"
                                           title="編集">
                                            ✏
                                        </a>
                                        <a href="<?= site_url('admin/tags/' . $tag['id'] . '/delete') ?>"
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
    <?php else: ?>
        <div class="card__content">
            <div class="empty-state">
                <div class="empty-state__icon">🏷️</div>
                <h3 class="empty-state__title">タグがありません</h3>
                <p class="empty-state__text">
                    まだタグが追加されていません。<br>
                    最初のタグを追加してみましょう。
                </p>
                <a href="<?= site_url('admin/tags/create') ?>" class="btn btn--primary">
                    タグを追加
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* タグ管理ページ専用スタイル */
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

.count {
    font-size: 14px;
    font-weight: 400;
    color: var(--admin-text-light);
}

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

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
