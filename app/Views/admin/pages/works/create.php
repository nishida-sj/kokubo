<?php
// 管理画面実績追加
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">実績追加</h1>
        <p class="page-description">新しい施工実績を追加します</p>
    </div>
    <div class="page-header__right">
        <a href="<?= site_url('admin/works') ?>" class="btn btn--outline">
            ← 一覧に戻る
        </a>
    </div>
</div>

<?php if (isset($errors) && !empty($errors)): ?>
    <div class="alert alert--error mb-3">
        <h4>入力内容をご確認ください</h4>
        <ul class="alert__list">
            <?php foreach ($errors as $fieldErrors): ?>
                <?php if (is_array($fieldErrors)): ?>
                    <?php foreach ($fieldErrors as $error): ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><?= h($fieldErrors) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= site_url('admin/works/store') ?>" method="POST" enctype="multipart/form-data" class="work-form">
    <?= Csrf::field() ?>

    <div class="form-layout">
        <!-- メインコンテンツ -->
        <div class="form-main">
            <!-- 基本情報 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">基本情報</h3>
                </div>
                <div class="card__content">
                    <div class="form-row">
                        <div class="form-group form-group--required">
                            <label for="title" class="form-label">タイトル</label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="<?= h($formData['title'] ?? '') ?>"
                                   class="form-input <?= isset($errors['title']) ? 'error' : '' ?>"
                                   required
                                   placeholder="例：伊勢市内 和風モダン住宅">
                            <div class="form-help">実績のタイトルを入力してください</div>
                        </div>

                        <div class="form-group">
                            <label for="slug" class="form-label">スラッグ</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="<?= h($formData['slug'] ?? '') ?>"
                                   class="form-input <?= isset($errors['slug']) ? 'error' : '' ?>"
                                   placeholder="ise-wafuu-modern（空欄で自動生成）">
                            <div class="form-help">URLに使用されます。空欄の場合はタイトルから自動生成されます</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-group--required">
                            <label for="category_id" class="form-label">カテゴリー</label>
                            <select id="category_id"
                                    name="category_id"
                                    class="form-select <?= isset($errors['category_id']) ? 'error' : '' ?>"
                                    required>
                                <option value="">選択してください</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"
                                            <?= (isset($formData['category_id']) && $formData['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= h($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="location" class="form-label">所在地</label>
                            <input type="text"
                                   id="location"
                                   name="location"
                                   value="<?= h($formData['location'] ?? '') ?>"
                                   class="form-input <?= isset($errors['location']) ? 'error' : '' ?>"
                                   placeholder="例：伊勢市小俣町">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">概要</label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="form-textarea <?= isset($errors['description']) ? 'error' : '' ?>"
                                  placeholder="実績の概要を入力してください（一覧ページで表示されます）"><?= h($formData['description'] ?? '') ?></textarea>
                        <div class="form-help">一覧ページで表示される概要文です</div>
                    </div>
                </div>
            </div>

            <!-- 詳細情報 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">詳細情報</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label for="content" class="form-label">詳細内容</label>
                        <textarea id="content"
                                  name="content"
                                  rows="12"
                                  class="form-textarea <?= isset($errors['content']) ? 'error' : '' ?>"
                                  placeholder="実績の詳細な内容を入力してください（詳細ページで表示されます）"><?= h($formData['content'] ?? '') ?></textarea>
                        <div class="form-help">詳細ページで表示される内容です。改行は自動で反映されます</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="construction_period" class="form-label">工期</label>
                            <input type="text"
                                   id="construction_period"
                                   name="construction_period"
                                   value="<?= h($formData['construction_period'] ?? '') ?>"
                                   class="form-input <?= isset($errors['construction_period']) ? 'error' : '' ?>"
                                   placeholder="例：6ヶ月">
                        </div>

                        <div class="form-group">
                            <label for="floor_area" class="form-label">延床面積</label>
                            <input type="text"
                                   id="floor_area"
                                   name="floor_area"
                                   value="<?= h($formData['floor_area'] ?? '') ?>"
                                   class="form-input <?= isset($errors['floor_area']) ? 'error' : '' ?>"
                                   placeholder="例：120㎡">
                        </div>

                        <div class="form-group">
                            <label for="structure" class="form-label">構造</label>
                            <input type="text"
                                   id="structure"
                                   name="structure"
                                   value="<?= h($formData['structure'] ?? '') ?>"
                                   class="form-input <?= isset($errors['structure']) ? 'error' : '' ?>"
                                   placeholder="例：木造軸組工法">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 画像アップロード -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">メイン画像</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label for="main_image" class="form-label">メイン画像</label>
                        <input type="file"
                               id="main_image"
                               name="main_image"
                               accept="image/*"
                               class="form-input <?= isset($errors['main_image']) ? 'error' : '' ?>"
                               data-preview="main-image-preview">
                        <div class="form-help">
                            一覧ページで表示されるメイン画像をアップロードしてください。<br>
                            推奨サイズ: 800×600px以上、JPEGまたはPNG形式、5MB以下
                        </div>
                        <div class="image-preview">
                            <img id="main-image-preview" class="image-preview__img" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO設定 -->
            <div class="card">
                <div class="card__header">
                    <h3 class="card__title">SEO設定</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">SEOタイトル</label>
                        <input type="text"
                               id="meta_title"
                               name="meta_title"
                               value="<?= h($formData['meta_title'] ?? '') ?>"
                               class="form-input <?= isset($errors['meta_title']) ? 'error' : '' ?>"
                               placeholder="空欄の場合はタイトルが使用されます">
                        <div class="form-help">検索結果に表示されるタイトルです（推奨: 60文字以内）</div>
                    </div>

                    <div class="form-group">
                        <label for="meta_description" class="form-label">SEO説明文</label>
                        <textarea id="meta_description"
                                  name="meta_description"
                                  rows="3"
                                  class="form-textarea <?= isset($errors['meta_description']) ? 'error' : '' ?>"
                                  placeholder="空欄の場合は概要が使用されます"><?= h($formData['meta_description'] ?? '') ?></textarea>
                        <div class="form-help">検索結果に表示される説明文です（推奨: 160文字以内）</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- サイドバー -->
        <div class="form-sidebar">
            <!-- 公開設定 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">公開設定</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   <?= (isset($formData['is_published']) && $formData['is_published']) ? 'checked' : 'checked' ?>>
                            <span class="checkbox-text">公開する</span>
                        </label>
                        <div class="form-help">チェックを外すと下書き状態になります</div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   <?= (isset($formData['is_featured']) && $formData['is_featured']) ? 'checked' : '' ?>>
                            <span class="checkbox-text">おすすめに表示</span>
                        </label>
                        <div class="form-help">トップページのおすすめに表示されます</div>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="form-label">表示順序</label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               value="<?= h($formData['sort_order'] ?? '0') ?>"
                               class="form-input"
                               min="0"
                               step="1">
                        <div class="form-help">数値が小さいほど上位に表示されます</div>
                    </div>
                </div>
            </div>

            <!-- タグ選択 -->
            <?php if (!empty($tags)): ?>
                <div class="card mb-3">
                    <div class="card__header">
                        <h3 class="card__title">タグ</h3>
                    </div>
                    <div class="card__content">
                        <div class="tag-selector">
                            <?php foreach ($tags as $tag): ?>
                                <label class="tag-item">
                                    <input type="checkbox"
                                           name="tags[]"
                                           value="<?= $tag['id'] ?>"
                                           class="tag-item__input">
                                    <span class="tag-item__text"><?= h($tag['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 保存ボタン -->
            <div class="card">
                <div class="card__content">
                    <button type="submit" class="btn btn--primary btn--block btn--large">
                        実績を保存
                    </button>
                    <div class="form-help text-center mt-2">
                        保存後、一覧ページに戻ります
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* 実績追加・編集フォーム専用スタイル */

/* フォームレイアウト */
.form-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
}

@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }

    .form-sidebar {
        order: -1;
    }
}

/* フォーム行 */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-row--three {
    grid-template-columns: 1fr 1fr 1fr;
}

@media (max-width: 768px) {
    .form-row--three {
        grid-template-columns: 1fr;
    }
}

/* 必須マーカー */
.form-group--required .form-label::after {
    content: ' *';
    color: var(--admin-error);
    font-weight: 700;
}

/* チェックボックス */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.checkbox-text {
    font-weight: 500;
}

/* タグセレクター */
.tag-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    background-color: var(--admin-bg);
    border: 1px solid var(--admin-border);
    border-radius: 16px;
    cursor: pointer;
    transition: all var(--admin-transition);
    font-size: 13px;
}

.tag-item:hover {
    background-color: #E3F2FD;
    border-color: var(--admin-primary);
}

.tag-item__input {
    margin: 0;
    width: 14px;
    height: 14px;
}

.tag-item__input:checked + .tag-item__text {
    font-weight: 600;
    color: var(--admin-primary);
}

/* 画像プレビュー */
.image-preview {
    margin-top: 12px;
}

.image-preview__img {
    max-width: 100%;
    max-height: 200px;
    border-radius: var(--admin-radius);
    box-shadow: var(--admin-shadow);
}

/* フォームヘルプ */
.form-help {
    font-size: 12px;
    color: var(--admin-text-light);
    margin-top: 4px;
    line-height: 1.4;
}

/* ワークフォーム */
.work-form {
    max-width: none;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 12px;
    }

    .page-header__right {
        align-self: stretch;
    }

    .btn {
        text-align: center;
    }
}

/* ファイル入力の装飾 */
input[type="file"] {
    padding: 8px;
    border: 2px dashed var(--admin-border);
    background-color: #FAFAFA;
    border-radius: var(--admin-radius);
    cursor: pointer;
    transition: all var(--admin-transition);
}

input[type="file"]:hover {
    border-color: var(--admin-primary);
    background-color: #F0F8FF;
}

input[type="file"]:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
}
</style>