<?php
// 採用情報管理
?>

<?php if (isset($successMessage)): ?>
    <div class="alert alert--success mb-3">
        <?= h($successMessage) ?>
    </div>
<?php endif; ?>

<!-- ページヘッダー -->
<div class="page-header mb-4">
    <h1 class="page-title">採用情報管理</h1>
    <p class="page-description">募集職種、福利厚生、応募資格などを編集できます</p>
</div>

<form method="POST" action="<?= site_url('admin/recruit/update') ?>">
    <!-- ページ設定 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">ページ設定</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-label">ページタイトル</label>
                <input type="text" name="page_title" class="form-input" value="<?= h($settings['page_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">ページサブタイトル</label>
                <input type="text" name="page_subtitle" class="form-input" value="<?= h($settings['page_subtitle'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- 募集職種1 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">募集職種 1</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-checkbox">
                    <input type="checkbox" name="job1_enabled" value="1" <?= (($settings['job1_enabled'] ?? '1') == '1') ? 'checked' : '' ?>>
                    <span>この職種を表示する</span>
                </label>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">アイコン（絵文字）</label>
                    <input type="text" name="job1_icon" class="form-input" value="<?= h($settings['job1_icon'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">職種名</label>
                    <input type="text" name="job1_title" class="form-input" value="<?= h($settings['job1_title'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">職種説明</label>
                <textarea name="job1_description" class="form-textarea" rows="4"><?= h($settings['job1_description'] ?? '') ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">雇用形態</label>
                    <input type="text" name="job1_employment_type" class="form-input" value="<?= h($settings['job1_employment_type'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">給与</label>
                    <input type="text" name="job1_salary" class="form-input" value="<?= h($settings['job1_salary'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">勤務時間</label>
                    <input type="text" name="job1_work_hours" class="form-input" value="<?= h($settings['job1_work_hours'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">休日・休暇</label>
                    <input type="text" name="job1_holidays" class="form-input" value="<?= h($settings['job1_holidays'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">必要な資格</label>
                    <input type="text" name="job1_qualifications" class="form-input" value="<?= h($settings['job1_qualifications'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">経験</label>
                    <input type="text" name="job1_experience" class="form-input" value="<?= h($settings['job1_experience'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- 募集職種2 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">募集職種 2</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-checkbox">
                    <input type="checkbox" name="job2_enabled" value="1" <?= (($settings['job2_enabled'] ?? '1') == '1') ? 'checked' : '' ?>>
                    <span>この職種を表示する</span>
                </label>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">アイコン（絵文字）</label>
                    <input type="text" name="job2_icon" class="form-input" value="<?= h($settings['job2_icon'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">職種名</label>
                    <input type="text" name="job2_title" class="form-input" value="<?= h($settings['job2_title'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">職種説明</label>
                <textarea name="job2_description" class="form-textarea" rows="4"><?= h($settings['job2_description'] ?? '') ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">雇用形態</label>
                    <input type="text" name="job2_employment_type" class="form-input" value="<?= h($settings['job2_employment_type'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">給与</label>
                    <input type="text" name="job2_salary" class="form-input" value="<?= h($settings['job2_salary'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">勤務時間</label>
                    <input type="text" name="job2_work_hours" class="form-input" value="<?= h($settings['job2_work_hours'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">休日・休暇</label>
                    <input type="text" name="job2_holidays" class="form-input" value="<?= h($settings['job2_holidays'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">必要な資格</label>
                    <input type="text" name="job2_qualifications" class="form-input" value="<?= h($settings['job2_qualifications'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">経験</label>
                    <input type="text" name="job2_experience" class="form-input" value="<?= h($settings['job2_experience'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- 福利厚生 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">福利厚生</h3>
        </div>
        <div class="card__content">
            <div class="form-group">
                <label class="form-label">福利厚生（1行につき「アイコン|タイトル|説明」の形式）</label>
                <textarea name="benefits" class="form-textarea" rows="10"><?= h($settings['benefits'] ?? '') ?></textarea>
                <p class="form-help">例: 🏥|健康保険|各種社会保険完備</p>
            </div>
        </div>
    </div>

    <!-- 応募資格 -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">応募資格</h3>
        </div>
        <div class="card__content">
            <div class="form-group">
                <label class="form-label">応募資格（1行につき1項目）</label>
                <textarea name="requirements" class="form-textarea" rows="8"><?= h($settings['requirements'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">応募CTA</h3>
        </div>
        <div class="card__content">
            <div class="form-group mb-3">
                <label class="form-label">CTAタイトル</label>
                <input type="text" name="cta_title" class="form-input" value="<?= h($settings['cta_title'] ?? '') ?>">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">CTA説明</label>
                <textarea name="cta_description" class="form-textarea" rows="3"><?= h($settings['cta_description'] ?? '') ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">ボタンテキスト</label>
                    <input type="text" name="cta_button_text" class="form-input" value="<?= h($settings['cta_button_text'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">ボタンURL</label>
                    <input type="text" name="cta_button_url" class="form-input" value="<?= h($settings['cta_button_url'] ?? '') ?>">
                </div>
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

.form-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.form-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-help {
    font-size: 12px;
    color: var(--admin-text-light);
    margin: 4px 0 0 0;
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
