<?php
// カテゴリー編集
?>

<!-- ページヘッダー -->
<div class="page-header">
    <h1 class="page-title">カテゴリー編集</h1>
</div>

<div class="card">
    <div class="card__content">
        <form method="POST" action="<?= site_url('admin/categories/' . $category['id'] . '/update') ?>" class="form">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label for="name" class="form-label">カテゴリー名 <span class="required">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-input"
                       required
                       value="<?= h($category['name']) ?>">
            </div>

            <div class="form-group">
                <label for="display_order" class="form-label">表示順</label>
                <input type="number"
                       id="display_order"
                       name="display_order"
                       class="form-input"
                       value="<?= h($category['display_order'] ?? 0) ?>"
                       min="0">
                <p class="form-help">数字が小さいほど上に表示されます</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">更新</button>
                <a href="<?= site_url('admin/categories') ?>" class="btn btn--secondary">キャンセル</a>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 24px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--admin-text);
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--admin-text);
}

.required {
    color: var(--admin-error);
}

.form-help {
    font-size: 14px;
    color: var(--admin-text-light);
    margin: 4px 0 0 0;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}
</style>
