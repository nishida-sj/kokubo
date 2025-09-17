<?php
// 管理画面お問い合わせ返信
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">お問い合わせ返信</h1>
        <p class="page-description">お客様への返信メールを作成します</p>
    </div>
    <div class="page-header__right">
        <a href="<?= site_url('admin/contacts/' . $contact['id']) ?>" class="btn btn--outline">
            ← 詳細に戻る
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

<div class="reply-layout">
    <!-- 返信フォーム -->
    <div class="reply-main">
        <form action="<?= site_url('admin/contacts/' . $contact['id'] . '/send-reply') ?>" method="POST" class="reply-form">
            <?= Csrf::field() ?>

            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">返信内容</h3>
                </div>
                <div class="card__content">
                    <div class="form-group form-group--required">
                        <label for="reply_subject" class="form-label">件名</label>
                        <input type="text"
                               id="reply_subject"
                               name="reply_subject"
                               value="<?= h($formData['reply_subject'] ?? 'Re: ' . $contact['subject']) ?>"
                               class="form-input <?= isset($errors['reply_subject']) ? 'error' : '' ?>"
                               required
                               placeholder="返信の件名を入力してください">
                        <div class="form-help">お客様に送信される件名です</div>
                    </div>

                    <div class="form-group form-group--required">
                        <label for="reply_message" class="form-label">返信内容</label>
                        <textarea id="reply_message"
                                  name="reply_message"
                                  rows="15"
                                  class="form-textarea <?= isset($errors['reply_message']) ? 'error' : '' ?>"
                                  required
                                  placeholder="お客様への返信内容を入力してください"><?= h($formData['reply_message'] ?? '') ?></textarea>
                        <div class="form-help">
                            返信内容を入力してください。自動的に署名やお問い合わせ内容の引用が追加されます。
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn--primary btn--large">
                            📧 返信を送信
                        </button>
                        <a href="<?= site_url('admin/contacts/' . $contact['id']) ?>" class="btn btn--outline btn--large">
                            キャンセル
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- サイドバー -->
    <div class="reply-sidebar">
        <!-- 元のお問い合わせ -->
        <div class="card mb-3">
            <div class="card__header">
                <h3 class="card__title">元のお問い合わせ</h3>
            </div>
            <div class="card__content">
                <div class="original-contact">
                    <div class="original-contact__meta">
                        <div class="meta-item">
                            <label>差出人</label>
                            <div class="value">👤 <?= h($contact['name']) ?></div>
                        </div>
                        <div class="meta-item">
                            <label>メール</label>
                            <div class="value">📧 <?= h($contact['email']) ?></div>
                        </div>
                        <div class="meta-item">
                            <label>受信日時</label>
                            <div class="value">📅 <?= format_date($contact['created_at'], 'Y/m/d H:i') ?></div>
                        </div>
                    </div>

                    <div class="original-contact__content">
                        <div class="content-section">
                            <h4>件名</h4>
                            <p><?= h($contact['subject']) ?></p>
                        </div>
                        <div class="content-section">
                            <h4>お問い合わせ内容</h4>
                            <div class="message-preview">
                                <?= nl2br(h($contact['message'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 返信テンプレート -->
        <div class="card">
            <div class="card__header">
                <h3 class="card__title">返信テンプレート</h3>
            </div>
            <div class="card__content">
                <div class="template-buttons">
                    <button type="button" class="template-btn" data-template="standard">
                        標準的な返信
                    </button>
                    <button type="button" class="template-btn" data-template="estimate">
                        お見積もりについて
                    </button>
                    <button type="button" class="template-btn" data-template="consultation">
                        ご相談について
                    </button>
                    <button type="button" class="template-btn" data-template="schedule">
                        打ち合わせ日程について
                    </button>
                </div>
                <div class="form-help">
                    テンプレートをクリックすると返信内容に挿入されます
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* お問い合わせ返信ページ専用スタイル */

/* 返信レイアウト */
.reply-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

@media (max-width: 1024px) {
    .reply-layout {
        grid-template-columns: 1fr;
    }

    .reply-sidebar {
        order: -1;
    }
}

/* フォームアクション */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}

/* 必須マーカー */
.form-group--required .form-label::after {
    content: ' *';
    color: var(--admin-error);
    font-weight: 700;
}

/* 元のお問い合わせ */
.original-contact__meta {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--admin-border);
}

.meta-item {
    margin-bottom: 8px;
}

.meta-item:last-child {
    margin-bottom: 0;
}

.meta-item label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--admin-text-muted);
    margin-bottom: 2px;
    text-transform: uppercase;
}

.meta-item .value {
    font-size: 13px;
    color: var(--admin-text);
}

.content-section {
    margin-bottom: 16px;
}

.content-section:last-child {
    margin-bottom: 0;
}

.content-section h4 {
    font-size: 12px;
    font-weight: 600;
    color: var(--admin-text-muted);
    margin-bottom: 6px;
    text-transform: uppercase;
}

.content-section p {
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
    margin: 0;
}

.message-preview {
    font-size: 12px;
    color: var(--admin-text);
    line-height: 1.5;
    max-height: 150px;
    overflow-y: auto;
    padding: 8px;
    background-color: #F8F9FA;
    border-radius: var(--admin-radius);
    border: 1px solid var(--admin-border);
}

/* テンプレートボタン */
.template-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
}

.template-btn {
    padding: 8px 12px;
    background-color: var(--admin-bg);
    border: 1px solid var(--admin-border);
    border-radius: var(--admin-radius);
    color: var(--admin-text);
    cursor: pointer;
    transition: all var(--admin-transition);
    font-size: 13px;
    text-align: left;
}

.template-btn:hover {
    background-color: var(--admin-primary);
    color: var(--admin-white);
    border-color: var(--admin-primary);
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
}
</style>

<script>
// 返信テンプレート機能
document.addEventListener('DOMContentLoaded', function() {
    const templateButtons = document.querySelectorAll('.template-btn');
    const replyTextarea = document.getElementById('reply_message');

    const templates = {
        'standard': `お忙しい中、お問い合わせいただきありがとうございます。

いただきましたお問い合わせについて回答いたします。



ご不明な点がございましたら、お気軽にお問い合わせください。
今後ともよろしくお願いいたします。`,

        'estimate': `お忙しい中、お見積もりのご依頼をいただきありがとうございます。

ご希望の工事について、詳細な見積もりを作成させていただきます。
より正確な見積もりのため、下記について教えていただけますでしょうか。

・工事場所：
・ご希望の工期：
・ご予算：
・その他ご要望：

詳細をお聞かせいただきましたら、現地調査の日程を調整させていただきます。`,

        'consultation': `この度は、ご相談いただきありがとうございます。

お客様のご要望を実現するため、最適なプランをご提案させていただきたく思います。

まずは、詳しいお話をお聞かせいただけますよう、お打ち合わせの機会をいただければと思います。

ご都合の良い日時をお教えください。
・第1希望：
・第2希望：
・第3希望：

お客様にとって最良のご提案ができるよう努めさせていただきます。`,

        'schedule': `お打ち合わせの件でご連絡いたします。

下記の日程でいかがでしょうか。

・日時：
・場所：
・所要時間：約1時間を予定

ご都合が悪い場合は、遠慮なくお申し付けください。
別の日程を調整させていただきます。

当日は、ご要望やご質問などお気軽にお聞かせください。`
    };

    templateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const templateType = this.dataset.template;
            const template = templates[templateType];

            if (template) {
                replyTextarea.value = template;
                replyTextarea.focus();
            }
        });
    });
});
</script>