<?php
// 管理画面サイト設定
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">サイト設定</h1>
        <p class="page-description">サイト全体の設定を管理できます</p>
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

<form action="<?= site_url('admin/settings/update') ?>" method="POST" class="settings-form">
    <?= Csrf::field() ?>

    <div class="settings-layout">
        <div class="settings-main">
            <!-- サイト基本情報 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">サイト基本情報</h3>
                </div>
                <div class="card__content">
                    <div class="form-group form-group--required">
                        <label for="site_title" class="form-label">サイトタイトル</label>
                        <input type="text"
                               id="site_title"
                               name="site_title"
                               value="<?= h($settings['site_title'] ?? '小久保植樹園') ?>"
                               class="form-input <?= isset($errors['site_title']) ? 'error' : '' ?>"
                               required
                               placeholder="小久保植樹園">
                        <div class="form-help">ブラウザのタイトルバーに表示されるサイト名です</div>
                    </div>

                    <div class="form-group form-group--required">
                        <label for="site_description" class="form-label">サイト説明文</label>
                        <textarea id="site_description"
                                  name="site_description"
                                  rows="3"
                                  class="form-textarea <?= isset($errors['site_description']) ? 'error' : '' ?>"
                                  required
                                  placeholder="伊勢市の工務店。新築・リフォーム・リノベーションに対応。"><?= h($settings['site_description'] ?? '') ?></textarea>
                        <div class="form-help">検索エンジンに表示される説明文です（推奨: 120文字以内）</div>
                    </div>

                    <div class="form-group">
                        <label for="site_keywords" class="form-label">サイトキーワード</label>
                        <input type="text"
                               id="site_keywords"
                               name="site_keywords"
                               value="<?= h($settings['site_keywords'] ?? '') ?>"
                               class="form-input <?= isset($errors['site_keywords']) ? 'error' : '' ?>"
                               placeholder="工務店,新築,リフォーム,伊勢市">
                        <div class="form-help">カンマ区切りでキーワードを入力してください</div>
                    </div>
                </div>
            </div>

            <!-- 会社情報 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">会社情報</h3>
                </div>
                <div class="card__content">
                    <div class="form-row">
                        <div class="form-group form-group--required">
                            <label for="company_name" class="form-label">会社名</label>
                            <input type="text"
                                   id="company_name"
                                   name="company_name"
                                   value="<?= h($settings['company_name'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_name']) ? 'error' : '' ?>"
                                   required
                                   placeholder="小久保植樹園">
                        </div>

                        <div class="form-group">
                            <label for="company_postal_code" class="form-label">郵便番号</label>
                            <input type="text"
                                   id="company_postal_code"
                                   name="company_postal_code"
                                   value="<?= h($settings['company_postal_code'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_postal_code']) ? 'error' : '' ?>"
                                   placeholder="516-0000">
                        </div>
                    </div>

                    <div class="form-group form-group--required">
                        <label for="company_address" class="form-label">住所</label>
                        <input type="text"
                               id="company_address"
                               name="company_address"
                               value="<?= h($settings['company_address'] ?? '') ?>"
                               class="form-input <?= isset($errors['company_address']) ? 'error' : '' ?>"
                               required
                               placeholder="三重県伊勢市...">
                    </div>

                    <div class="form-row">
                        <div class="form-group form-group--required">
                            <label for="company_tel" class="form-label">電話番号</label>
                            <input type="tel"
                                   id="company_tel"
                                   name="company_tel"
                                   value="<?= h($settings['company_tel'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_tel']) ? 'error' : '' ?>"
                                   required
                                   placeholder="0596-00-0000">
                        </div>

                        <div class="form-group">
                            <label for="company_fax" class="form-label">FAX番号</label>
                            <input type="tel"
                                   id="company_fax"
                                   name="company_fax"
                                   value="<?= h($settings['company_fax'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_fax']) ? 'error' : '' ?>"
                                   placeholder="0596-00-0000">
                        </div>
                    </div>

                    <div class="form-group form-group--required">
                        <label for="company_email" class="form-label">メールアドレス</label>
                        <input type="email"
                               id="company_email"
                               name="company_email"
                               value="<?= h($settings['company_email'] ?? '') ?>"
                               class="form-input <?= isset($errors['company_email']) ? 'error' : '' ?>"
                               required
                               placeholder="info@kokubosyokuju.geo.jp">
                    </div>

                    <div class="form-group">
                        <label for="notification_email" class="form-label">通知メールアドレス</label>
                        <input type="email"
                               id="notification_email"
                               name="notification_email"
                               value="<?= h($settings['notification_email'] ?? '') ?>"
                               class="form-input <?= isset($errors['notification_email']) ? 'error' : '' ?>"
                               placeholder="notifications@example.com">
                        <div class="form-help">お問い合わせがあった際に通知を受け取るメールアドレスです。空欄の場合は通知されません。</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_business_hours" class="form-label">営業時間</label>
                            <input type="text"
                                   id="company_business_hours"
                                   name="company_business_hours"
                                   value="<?= h($settings['company_business_hours'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_business_hours']) ? 'error' : '' ?>"
                                   placeholder="9:00〜18:00">
                        </div>

                        <div class="form-group">
                            <label for="company_holiday" class="form-label">定休日</label>
                            <input type="text"
                                   id="company_holiday"
                                   name="company_holiday"
                                   value="<?= h($settings['company_holiday'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_holiday']) ? 'error' : '' ?>"
                                   placeholder="日曜日・祝日">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_established" class="form-label">設立年</label>
                            <input type="text"
                                   id="company_established"
                                   name="company_established"
                                   value="<?= h($settings['company_established'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_established']) ? 'error' : '' ?>"
                                   placeholder="昭和○○年">
                        </div>

                        <div class="form-group">
                            <label for="company_license" class="form-label">許可・資格</label>
                            <input type="text"
                                   id="company_license"
                                   name="company_license"
                                   value="<?= h($settings['company_license'] ?? '') ?>"
                                   class="form-input <?= isset($errors['company_license']) ? 'error' : '' ?>"
                                   placeholder="建設業許可（般-○○）第○○号">
                        </div>
                    </div>
                </div>
            </div>

            <!-- アナリティクス設定 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">アナリティクス設定</h3>
                </div>
                <div class="card__content">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
                            <input type="text"
                                   id="google_analytics_id"
                                   name="google_analytics_id"
                                   value="<?= h($settings['google_analytics_id'] ?? '') ?>"
                                   class="form-input <?= isset($errors['google_analytics_id']) ? 'error' : '' ?>"
                                   placeholder="G-XXXXXXXXXX">
                            <div class="form-help">Google Analytics 4の測定IDを入力してください</div>
                        </div>

                        <div class="form-group">
                            <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                            <input type="text"
                                   id="google_tag_manager_id"
                                   name="google_tag_manager_id"
                                   value="<?= h($settings['google_tag_manager_id'] ?? '') ?>"
                                   class="form-input <?= isset($errors['google_tag_manager_id']) ? 'error' : '' ?>"
                                   placeholder="GTM-XXXXXXX">
                            <div class="form-help">Google Tag ManagerのコンテナIDを入力してください</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SNS設定 -->
            <div class="card">
                <div class="card__header">
                    <h3 class="card__title">SNS設定</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label for="facebook_url" class="form-label">Facebook URL</label>
                        <input type="url"
                               id="facebook_url"
                               name="facebook_url"
                               value="<?= h($settings['facebook_url'] ?? '') ?>"
                               class="form-input <?= isset($errors['facebook_url']) ? 'error' : '' ?>"
                               placeholder="https://www.facebook.com/your-page">
                    </div>

                    <div class="form-group">
                        <label for="instagram_url" class="form-label">Instagram URL</label>
                        <input type="url"
                               id="instagram_url"
                               name="instagram_url"
                               value="<?= h($settings['instagram_url'] ?? '') ?>"
                               class="form-input <?= isset($errors['instagram_url']) ? 'error' : '' ?>"
                               placeholder="https://www.instagram.com/your-account">
                    </div>

                    <div class="form-group">
                        <label for="twitter_url" class="form-label">Twitter URL</label>
                        <input type="url"
                               id="twitter_url"
                               name="twitter_url"
                               value="<?= h($settings['twitter_url'] ?? '') ?>"
                               class="form-input <?= isset($errors['twitter_url']) ? 'error' : '' ?>"
                               placeholder="https://twitter.com/your-account">
                    </div>

                    <div class="form-group">
                        <label for="youtube_url" class="form-label">YouTube URL</label>
                        <input type="url"
                               id="youtube_url"
                               name="youtube_url"
                               value="<?= h($settings['youtube_url'] ?? '') ?>"
                               class="form-input <?= isset($errors['youtube_url']) ? 'error' : '' ?>"
                               placeholder="https://www.youtube.com/channel/your-channel">
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-sidebar">
            <!-- メンテナンス設定 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">メンテナンス設定</h3>
                </div>
                <div class="card__content">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox"
                                   name="maintenance_mode"
                                   value="1"
                                   <?= (isset($settings['maintenance_mode']) && $settings['maintenance_mode']) ? 'checked' : '' ?>>
                            <span class="checkbox-text">メンテナンスモード</span>
                        </label>
                        <div class="form-help">
                            有効にすると一般ユーザーがサイトにアクセスできなくなります
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maintenance_message" class="form-label">メンテナンスメッセージ</label>
                        <textarea id="maintenance_message"
                                  name="maintenance_message"
                                  rows="4"
                                  class="form-textarea <?= isset($errors['maintenance_message']) ? 'error' : '' ?>"
                                  placeholder="現在、サイトメンテナンス中です。しばらくお待ちください。"><?= h($settings['maintenance_message'] ?? '') ?></textarea>
                        <div class="form-help">メンテナンス中に表示されるメッセージです</div>
                    </div>
                </div>
            </div>

            <!-- 保存ボタン -->
            <div class="card">
                <div class="card__content">
                    <button type="submit" class="btn btn--primary btn--block btn--large">
                        ⚙ 設定を保存
                    </button>
                    <div class="form-help text-center mt-2">
                        設定はすぐに反映されます
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
/* サイト設定ページ専用スタイル */

/* 設定レイアウト */
.settings-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
}

@media (max-width: 1024px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }

    .settings-sidebar {
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

/* フォームヘルプ */
.form-help {
    font-size: 12px;
    color: var(--admin-text-light);
    margin-top: 4px;
    line-height: 1.4;
}

/* 設定フォーム */
.settings-form {
    max-width: none;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 12px;
    }
}
</style>