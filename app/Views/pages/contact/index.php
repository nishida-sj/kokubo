<?php
// お問い合わせページ
?>

<!-- ページヘッダー -->
<section class="page-header">
    <div class="page-header__background">
        <img src="<?= asset_url('img/contact-header-bg.jpg') ?>" alt="お問い合わせ" class="page-header__bg-img">
        <div class="page-header__overlay"></div>
    </div>

    <div class="container">
        <div class="page-header__content">
            <h1 class="page-header__title">お問い合わせ</h1>
            <p class="page-header__description">
                新築・リフォーム・増改築に関するご相談やお見積りは無料です。<br>
                お気軽にお問い合わせください。
            </p>

            <!-- パンくずリスト -->
            <nav class="breadcrumb" aria-label="パンくずリスト">
                <ol class="breadcrumb__list">
                    <li class="breadcrumb__item">
                        <a href="<?= site_url() ?>" class="breadcrumb__link">ホーム</a>
                    </li>
                    <li class="breadcrumb__item breadcrumb__item--current">
                        お問い合わせ
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- 成功メッセージ -->
<?php if (isset($success) && $success): ?>
    <section class="contact-success">
        <div class="container">
            <div class="contact-success__content">
                <div class="contact-success__icon">✓</div>
                <h2 class="contact-success__title">お問い合わせを受け付けました</h2>
                <p class="contact-success__message">
                    お問い合わせありがとうございます。<br>
                    内容を確認の上、3営業日以内にご返信させていただきます。<br>
                    自動返信メールをお送りしておりますので、ご確認ください。
                </p>
                <div class="contact-success__actions">
                    <a href="<?= site_url() ?>" class="btn btn--primary">ホームに戻る</a>
                    <a href="<?= site_url('works') ?>" class="btn btn--outline">施工実績を見る</a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- お問い合わせ方法 -->
<section class="contact-methods">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">お問い合わせ方法</h2>
            <p class="section-description">
                ご都合の良い方法でお気軽にお問い合わせください。
            </p>
        </div>

        <div class="contact-methods__grid">
            <!-- 電話 -->
            <div class="contact-method">
                <div class="contact-method__icon">📞</div>
                <h3 class="contact-method__title">お電話でのお問い合わせ</h3>
                <div class="contact-method__content">
                    <a href="tel:0596-00-0000" class="contact-method__tel">0596-00-0000</a>
                    <p class="contact-method__hours">営業時間: 平日 8:00-18:00 / 土曜 8:00-17:00</p>
                    <p class="contact-method__note">お急ぎの方はお電話が便利です</p>
                </div>
            </div>

            <!-- メール -->
            <div class="contact-method">
                <div class="contact-method__icon">✉</div>
                <h3 class="contact-method__title">メールでのお問い合わせ</h3>
                <div class="contact-method__content">
                    <p class="contact-method__email">info@kokubosyokuju.geo.jp</p>
                    <p class="contact-method__response">3営業日以内にご返信いたします</p>
                    <p class="contact-method__note">24時間受付しております</p>
                </div>
            </div>

            <!-- フォーム -->
            <div class="contact-method contact-method--featured">
                <div class="contact-method__icon">📝</div>
                <h3 class="contact-method__title">お問い合わせフォーム</h3>
                <div class="contact-method__content">
                    <p class="contact-method__description">
                        下記のフォームからお気軽にお問い合わせください
                    </p>
                    <a href="#contact-form" class="btn btn--primary">フォームを利用する</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- お問い合わせフォーム -->
<?php if (!isset($success) || !$success): ?>
    <section class="contact-form-section" id="contact-form">
        <div class="container">
            <div class="contact-form-wrapper">
                <div class="contact-form__header">
                    <h2 class="contact-form__title">お問い合わせフォーム</h2>
                    <p class="contact-form__description">
                        下記項目にご記入の上、送信ボタンを押してください。<br>
                        <span class="required-mark">*</span> は必須項目です。
                    </p>
                </div>

                <!-- エラーメッセージ -->
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="error-messages" id="errorMessages">
                        <h3 class="error-messages__title">入力内容をご確認ください</h3>
                        <ul class="error-messages__list">
                            <?php foreach ($errors as $fieldErrors): ?>
                                <?php if (is_array($fieldErrors)): ?>
                                    <?php foreach ($fieldErrors as $error): ?>
                                        <li class="error-messages__item"><?= h($error) ?></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="error-messages__item"><?= h($fieldErrors) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('contact/send') ?>" method="POST" class="contact-form" id="contactForm" data-ajax>
                    <?= Csrf::field() ?>

                    <div class="form-grid">
                        <!-- お名前 -->
                        <div class="form-group form-group--required">
                            <label for="name" class="form-label">
                                お名前 <span class="required-mark">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="<?= h($formData['name'] ?? '') ?>"
                                   class="form-input <?= isset($errors['name']) ? 'error' : '' ?>"
                                   required
                                   placeholder="山田太郎">
                        </div>

                        <!-- メールアドレス -->
                        <div class="form-group form-group--required">
                            <label for="email" class="form-label">
                                メールアドレス <span class="required-mark">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="<?= h($formData['email'] ?? '') ?>"
                                   class="form-input <?= isset($errors['email']) ? 'error' : '' ?>"
                                   required
                                   placeholder="yamada@example.com">
                        </div>

                        <!-- 電話番号 -->
                        <div class="form-group">
                            <label for="phone" class="form-label">電話番号</label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="<?= h($formData['phone'] ?? '') ?>"
                                   class="form-input <?= isset($errors['phone']) ? 'error' : '' ?>"
                                   placeholder="0596-00-0000">
                        </div>

                        <!-- ご住所 -->
                        <div class="form-group">
                            <label for="address" class="form-label">ご住所</label>
                            <input type="text"
                                   id="address"
                                   name="address"
                                   value="<?= h($formData['address'] ?? '') ?>"
                                   class="form-input <?= isset($errors['address']) ? 'error' : '' ?>"
                                   placeholder="三重県伊勢市○○町○○番地">
                        </div>
                    </div>

                    <!-- 件名 -->
                    <div class="form-group">
                        <label for="subject" class="form-label">件名</label>
                        <input type="text"
                               id="subject"
                               name="subject"
                               value="<?= h($formData['subject'] ?? '') ?>"
                               class="form-input <?= isset($errors['subject']) ? 'error' : '' ?>"
                               placeholder="新築住宅の相談">
                    </div>

                    <!-- お問い合わせ内容 -->
                    <div class="form-group form-group--required">
                        <label for="message" class="form-label">
                            お問い合わせ内容 <span class="required-mark">*</span>
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="8"
                                  class="form-textarea <?= isset($errors['message']) ? 'error' : '' ?>"
                                  required
                                  placeholder="お問い合わせ内容をできるだけ詳しくご記入ください。&#10;&#10;例：&#10;・新築住宅を検討中&#10;・予算は○○万円程度&#10;・土地は○○市内で探している&#10;・家族構成は夫婦+子供2人&#10;・希望の間取りや仕様など"><?= h($formData['message'] ?? '') ?></textarea>
                        <div class="form-hint">
                            お困りの内容やご希望を詳しくお聞かせください。より具体的にお答えできます。
                        </div>
                    </div>

                    <!-- プライバシーポリシー同意 -->
                    <div class="form-group form-group--checkbox">
                        <label class="form-checkbox">
                            <input type="checkbox" name="privacy_agree" value="1" required class="form-checkbox__input">
                            <span class="form-checkbox__mark"></span>
                            <span class="form-checkbox__text">
                                <a href="#privacy-policy" class="form-checkbox__link">プライバシーポリシー</a>に同意します <span class="required-mark">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- 送信ボタン -->
                    <div class="form-submit">
                        <button type="submit" class="btn btn--primary btn--large form-submit__button">
                            お問い合わせを送信する
                        </button>
                        <p class="form-submit__note">
                            送信後、確認メールをお送りいたします。
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 会社情報 -->
<section class="company-info">
    <div class="container">
        <div class="company-info__grid">
            <div class="company-info__content">
                <h2 class="company-info__title">会社概要</h2>
                <dl class="company-info__details">
                    <div class="company-info__item">
                        <dt class="company-info__label">会社名</dt>
                        <dd class="company-info__value">小久保工務店</dd>
                    </div>
                    <div class="company-info__item">
                        <dt class="company-info__label">所在地</dt>
                        <dd class="company-info__value">
                            〒516-0000<br>
                            三重県伊勢市○○町○○番地
                        </dd>
                    </div>
                    <div class="company-info__item">
                        <dt class="company-info__label">電話番号</dt>
                        <dd class="company-info__value">
                            <a href="tel:0596-00-0000" class="company-info__tel">0596-00-0000</a>
                        </dd>
                    </div>
                    <div class="company-info__item">
                        <dt class="company-info__label">営業時間</dt>
                        <dd class="company-info__value">平日 8:00-18:00 / 土曜 8:00-17:00</dd>
                    </div>
                    <div class="company-info__item">
                        <dt class="company-info__label">定休日</dt>
                        <dd class="company-info__value">日曜・祝日</dd>
                    </div>
                </dl>
            </div>

            <div class="company-info__map">
                <div class="map-container">
                    <div class="map-placeholder">
                        <div class="map-placeholder__content">
                            <div class="map-placeholder__icon">📍</div>
                            <p class="map-placeholder__text">
                                三重県伊勢市○○町○○番地<br>
                                <small>※実際の地図はGoogleマップなどを埋め込み</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- プライバシーポリシー（簡易版） -->
<section class="privacy-policy" id="privacy-policy">
    <div class="container">
        <div class="privacy-policy__content">
            <h2 class="privacy-policy__title">プライバシーポリシー</h2>
            <div class="privacy-policy__text">
                <p>小久保工務店（以下「当社」）は、お客様の個人情報の保護に関して、以下のとおりプライバシーポリシーを定めます。</p>

                <h3>個人情報の取得について</h3>
                <p>当社は、お客様からお問い合わせやご相談をいただく際に、氏名、住所、電話番号、メールアドレス等の個人情報を取得させていただきます。</p>

                <h3>個人情報の利用目的</h3>
                <p>取得した個人情報は、以下の目的で利用いたします。</p>
                <ul>
                    <li>お客様からのお問い合わせやご相談への回答</li>
                    <li>当社サービスに関するご案内</li>
                    <li>その他、お客様との円滑なコミュニケーション</li>
                </ul>

                <h3>個人情報の第三者提供</h3>
                <p>当社は、法令に基づく場合を除き、お客様の同意なしに個人情報を第三者に提供することはありません。</p>

                <h3>お問い合わせ窓口</h3>
                <p>個人情報の取り扱いに関するお問い合わせは、下記までご連絡ください。</p>
                <p>
                    小久保工務店<br>
                    TEL: 0596-00-0000<br>
                    Email: info@kokubosyokuju.geo.jp
                </p>
            </div>
        </div>
    </div>
</section>

<style>
/* お問い合わせページ専用スタイル */

/* 成功メッセージ */
.contact-success {
    padding: var(--space-16) 0;
    background-color: var(--c-success);
    color: var(--c-white);
    text-align: center;
}

.contact-success__content {
    max-width: 600px;
    margin: 0 auto;
}

.contact-success__icon {
    width: 80px;
    height: 80px;
    background-color: var(--c-white);
    color: var(--c-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--fs-3xl);
    font-weight: 700;
    margin: 0 auto var(--space-6);
}

.contact-success__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.contact-success__message {
    font-size: var(--fs-lg);
    line-height: 1.7;
    margin-bottom: var(--space-8);
    opacity: 0.9;
}

.contact-success__actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    align-items: center;
}

@media (min-width: 640px) {
    .contact-success__actions {
        flex-direction: row;
        justify-content: center;
    }
}

/* お問い合わせ方法 */
.contact-methods {
    padding: var(--space-16) 0;
    background-color: var(--c-white);
}

.contact-methods__grid {
    display: grid;
    gap: var(--space-8);
}

@media (min-width: 768px) {
    .contact-methods__grid {
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-12);
    }
}

.contact-method {
    text-align: center;
    padding: var(--space-8);
    background-color: var(--c-bg);
    border-radius: var(--radius-lg);
    transition: all var(--transition-base);
}

.contact-method:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.contact-method--featured {
    background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-secondary) 100%);
    color: var(--c-white);
}

.contact-method__icon {
    font-size: var(--fs-4xl);
    margin-bottom: var(--space-4);
}

.contact-method__title {
    font-size: var(--fs-xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
    color: var(--c-text);
}

.contact-method--featured .contact-method__title {
    color: var(--c-white);
}

.contact-method__tel {
    display: block;
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-primary);
    text-decoration: none;
    margin-bottom: var(--space-2);
    transition: color var(--transition-base);
}

.contact-method__tel:hover {
    color: var(--c-primary-dark);
}

.contact-method__email {
    font-size: var(--fs-lg);
    color: var(--c-primary);
    font-weight: 500;
    margin-bottom: var(--space-2);
}

.contact-method__hours,
.contact-method__response,
.contact-method__note,
.contact-method__description {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.6;
    margin-bottom: var(--space-2);
}

.contact-method--featured .contact-method__description {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: var(--space-4);
}

.contact-method__note {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
}

/* フォームセクション */
.contact-form-section {
    padding: var(--space-16) 0 var(--space-24);
    background-color: var(--c-bg);
}

.contact-form-wrapper {
    max-width: 800px;
    margin: 0 auto;
    background-color: var(--c-white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.contact-form__header {
    padding: var(--space-8) var(--space-8) var(--space-6);
    background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-secondary) 100%);
    color: var(--c-white);
    text-align: center;
}

.contact-form__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.contact-form__description {
    font-size: var(--fs-base);
    line-height: 1.7;
    opacity: 0.9;
}

.required-mark {
    color: var(--c-error);
    font-weight: 700;
}

/* エラーメッセージ */
.error-messages {
    margin: var(--space-6) var(--space-8) 0;
    padding: var(--space-6);
    background-color: var(--c-error);
    color: var(--c-white);
    border-radius: var(--radius-lg);
}

.error-messages__title {
    font-size: var(--fs-lg);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.error-messages__list {
    list-style: none;
}

.error-messages__item {
    padding: var(--space-1) 0;
    position: relative;
    padding-left: var(--space-6);
}

.error-messages__item::before {
    content: '•';
    position: absolute;
    left: 0;
    font-weight: 700;
}

/* フォーム */
.contact-form {
    padding: var(--space-8);
}

.form-grid {
    display: grid;
    gap: var(--space-6);
    margin-bottom: var(--space-6);
}

@media (min-width: 768px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-8);
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.form-group--required .form-label {
    font-weight: 600;
}

.form-label {
    font-size: var(--fs-base);
    font-weight: 500;
    color: var(--c-text);
}

.form-input,
.form-textarea {
    padding: var(--space-3) var(--space-4);
    border: 2px solid var(--c-gray-300);
    border-radius: var(--radius-md);
    font-size: var(--fs-base);
    font-family: inherit;
    transition: all var(--transition-base);
    background-color: var(--c-white);
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--c-primary);
    box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
}

.form-input.error,
.form-textarea.error {
    border-color: var(--c-error);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.6;
}

.form-hint {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
    line-height: 1.5;
}

/* チェックボックス */
.form-group--checkbox {
    margin: var(--space-6) 0;
}

.form-checkbox {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    cursor: pointer;
    font-size: var(--fs-base);
    line-height: 1.6;
}

.form-checkbox__input {
    display: none;
}

.form-checkbox__mark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--c-gray-400);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
    transition: all var(--transition-base);
}

.form-checkbox__input:checked + .form-checkbox__mark {
    background-color: var(--c-primary);
    border-color: var(--c-primary);
}

.form-checkbox__input:checked + .form-checkbox__mark::after {
    content: '✓';
    color: var(--c-white);
    font-size: var(--fs-sm);
    font-weight: 700;
}

.form-checkbox__text {
    color: var(--c-text);
}

.form-checkbox__link {
    color: var(--c-primary);
    text-decoration: none;
    font-weight: 500;
}

.form-checkbox__link:hover {
    text-decoration: underline;
}

/* 送信ボタン */
.form-submit {
    text-align: center;
    margin-top: var(--space-8);
}

.form-submit__button {
    min-width: 300px;
    margin-bottom: var(--space-4);
}

.form-submit__button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-submit__note {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
    line-height: 1.5;
}

/* 会社情報 */
.company-info {
    padding: var(--space-16) 0;
    background-color: var(--c-white);
}

.company-info__grid {
    display: grid;
    gap: var(--space-12);
}

@media (min-width: 1024px) {
    .company-info__grid {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-16);
    }
}

.company-info__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-8);
}

.company-info__details {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}

.company-info__item {
    display: grid;
    grid-template-columns: 100px 1fr;
    gap: var(--space-4);
    align-items: start;
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--c-gray-200);
}

@media (min-width: 768px) {
    .company-info__item {
        grid-template-columns: 120px 1fr;
    }
}

.company-info__label {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
    font-weight: 500;
}

.company-info__value {
    font-size: var(--fs-base);
    color: var(--c-text);
    line-height: 1.6;
}

.company-info__tel {
    color: var(--c-primary);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-base);
}

.company-info__tel:hover {
    color: var(--c-primary-dark);
}

/* 地図 */
.map-container {
    width: 100%;
    height: 300px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
}

.map-placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--c-gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
}

.map-placeholder__content {
    text-align: center;
    color: var(--c-text-muted);
}

.map-placeholder__icon {
    font-size: var(--fs-3xl);
    margin-bottom: var(--space-4);
}

.map-placeholder__text {
    font-size: var(--fs-base);
    line-height: 1.6;
}

/* プライバシーポリシー */
.privacy-policy {
    padding: var(--space-16) 0;
    background-color: var(--c-gray-100);
}

.privacy-policy__content {
    max-width: 800px;
    margin: 0 auto;
}

.privacy-policy__title {
    font-size: var(--fs-2xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-8);
    text-align: center;
}

.privacy-policy__text {
    font-size: var(--fs-base);
    line-height: 1.7;
    color: var(--c-text);
}

.privacy-policy__text h3 {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-text);
    margin: var(--space-8) 0 var(--space-4);
}

.privacy-policy__text ul {
    margin: var(--space-4) 0;
    padding-left: var(--space-6);
}

.privacy-policy__text li {
    list-style: disc;
    margin-bottom: var(--space-2);
}

/* レスポンシブ調整 */
@media (max-width: 767px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .contact-form {
        padding: var(--space-6);
    }

    .contact-form__header {
        padding: var(--space-6);
    }

    .form-submit__button {
        min-width: 100%;
    }

    .company-info__item {
        grid-template-columns: 1fr;
        gap: var(--space-2);
        text-align: left;
    }
}
</style>