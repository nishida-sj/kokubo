<?php
// 会社案内管理
?>

<?php if (isset($successMessage)): ?>
    <div class="alert alert--success mb-3">
        <?= h($successMessage) ?>
    </div>
<?php endif; ?>

<!-- ページヘッダー -->
<div class="page-header mb-4">
    <h1 class="page-title">会社案内管理</h1>
    <p class="page-description">代表挨拶と会社概要を編集できます</p>
</div>

<form method="POST" action="<?= site_url('admin/company/update') ?>">
    <!-- 代表挨拶 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">代表挨拶</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-label">タイトル</label>
                <input type="text"
                       name="greeting_title"
                       class="form-input"
                       value="<?= h($settings['greeting_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">内容</label>
                <textarea name="greeting_content"
                          class="form-textarea"
                          rows="8"><?= h($settings['greeting_content'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- 会社概要 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">会社概要</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-label">会社名</label>
                <input type="text"
                       name="overview_name"
                       class="form-input"
                       value="<?= h($settings['overview_name'] ?? '') ?>">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">設立</label>
                <input type="text"
                       name="overview_established"
                       class="form-input"
                       value="<?= h($settings['overview_established'] ?? '') ?>">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">代表者</label>
                <input type="text"
                       name="overview_representative"
                       class="form-input"
                       value="<?= h($settings['overview_representative'] ?? '') ?>">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">従業員数</label>
                <input type="text"
                       name="overview_employees"
                       class="form-input"
                       value="<?= h($settings['overview_employees'] ?? '') ?>">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">事業内容</label>
                <textarea name="overview_business"
                          class="form-textarea"
                          rows="4"><?= h($settings['overview_business'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">対応エリア</label>
                <input type="text"
                       name="overview_area"
                       class="form-input"
                       value="<?= h($settings['overview_area'] ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">保存する</button>
    </div>
</form>

<style>
.page-header {
    margin-bottom: 24px;
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

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--admin-text);
}

.form-textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--admin-border);
    border-radius: var(--admin-radius);
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--admin-primary);
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}

.mb-3 {
    margin-bottom: 20px;
}

.mb-4 {
    margin-bottom: 30px;
}
</style>
