<?php
// 管理画面お問い合わせ詳細
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">お問い合わせ詳細</h1>
        <p class="page-description">お問い合わせの詳細内容を確認できます</p>
    </div>
    <div class="page-header__right">
        <a href="<?= site_url('admin/contacts') ?>" class="btn btn--outline">
            ← 一覧に戻る
        </a>
    </div>
</div>

<div class="contact-detail">
    <!-- ステータス・アクションエリア -->
    <div class="card mb-3">
        <div class="card__header">
            <h3 class="card__title">
                ステータス・操作
            </h3>
        </div>
        <div class="card__content">
            <div class="status-action-bar">
                <div class="status-action-bar__left">
                    <div class="status-badges">
                        <?php if (!$contact['is_read']): ?>
                            <span class="status status--unread">未読</span>
                        <?php else: ?>
                            <span class="status status--read">既読</span>
                        <?php endif; ?>
                        <?php if ($contact['is_replied']): ?>
                            <span class="status status--replied">返信済み</span>
                        <?php endif; ?>
                    </div>
                    <div class="received-date">
                        📅 受信日時: <?= format_date($contact['created_at'], 'Y年m月d日 H:i') ?>
                    </div>
                    <?php if ($contact['is_replied']): ?>
                        <div class="replied-date">
                            📧 返信日時: <?= format_date($contact['reply_sent_at'], 'Y年m月d日 H:i') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="status-action-bar__right">
                    <?php if (!$contact['is_replied']): ?>
                        <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/reply') ?>"
                           class="btn btn--primary">
                            📧 返信する
                        </a>
                    <?php endif; ?>
                    <?php if (!$contact['is_read']): ?>
                        <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/mark-read') ?>"
                           class="btn btn--outline">
                            ✓ 既読にする
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/mark-unread') ?>"
                           class="btn btn--outline">
                            ✗ 未読にする
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/delete') ?>"
                       class="btn btn--danger btn--outline confirm-delete">
                        🗑 削除
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-layout">
        <div class="detail-main">
            <!-- お問い合わせ内容 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">お問い合わせ内容</h3>
                </div>
                <div class="card__content">
                    <div class="contact-message">
                        <div class="contact-message__subject">
                            <h4>件名</h4>
                            <p><?= h($contact['subject']) ?></p>
                        </div>
                        <div class="contact-message__content">
                            <h4>お問い合わせ内容</h4>
                            <div class="message-content">
                                <?= nl2br(h($contact['message'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 返信内容（返信済みの場合） -->
            <?php if ($contact['is_replied']): ?>
                <div class="card">
                    <div class="card__header">
                        <h3 class="card__title">返信内容</h3>
                    </div>
                    <div class="card__content">
                        <div class="reply-content">
                            <div class="reply-content__subject">
                                <h4>返信件名</h4>
                                <p><?= h($contact['reply_subject']) ?></p>
                            </div>
                            <div class="reply-content__message">
                                <h4>返信内容</h4>
                                <div class="message-content">
                                    <?= nl2br(h($contact['reply_message'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="detail-sidebar">
            <!-- お客様情報 -->
            <div class="card mb-3">
                <div class="card__header">
                    <h3 class="card__title">お客様情報</h3>
                </div>
                <div class="card__content">
                    <div class="customer-info">
                        <div class="customer-info__item">
                            <label>お名前</label>
                            <div class="value">👤 <?= h($contact['name']) ?></div>
                        </div>

                        <div class="customer-info__item">
                            <label>メールアドレス</label>
                            <div class="value">
                                📧 <a href="mailto:<?= h($contact['email']) ?>"><?= h($contact['email']) ?></a>
                            </div>
                        </div>

                        <?php if (!empty($contact['phone'])): ?>
                            <div class="customer-info__item">
                                <label>電話番号</label>
                                <div class="value">
                                    📞 <a href="tel:<?= h($contact['phone']) ?>"><?= h($contact['phone']) ?></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($contact['company'])): ?>
                            <div class="customer-info__item">
                                <label>会社名</label>
                                <div class="value">🏢 <?= h($contact['company']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 詳細情報 -->
            <div class="card">
                <div class="card__header">
                    <h3 class="card__title">詳細情報</h3>
                </div>
                <div class="card__content">
                    <div class="detail-info">
                        <div class="detail-info__item">
                            <label>受信日時</label>
                            <div class="value"><?= format_date($contact['created_at'], 'Y年m月d日 H:i:s') ?></div>
                        </div>

                        <div class="detail-info__item">
                            <label>IPアドレス</label>
                            <div class="value"><?= h($contact['ip_address'] ?? '不明') ?></div>
                        </div>

                        <div class="detail-info__item">
                            <label>ユーザーエージェント</label>
                            <div class="value text-small"><?= h($contact['user_agent'] ?? '不明') ?></div>
                        </div>

                        <?php if ($contact['is_replied']): ?>
                            <div class="detail-info__item">
                                <label>返信日時</label>
                                <div class="value"><?= format_date($contact['reply_sent_at'], 'Y年m月d日 H:i:s') ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* お問い合わせ詳細ページ専用スタイル */

/* ステータス・アクションバー */
.status-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.status-action-bar__left {
    flex: 1;
}

.status-action-bar__right {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.status-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.received-date,
.replied-date {
    font-size: 13px;
    color: var(--admin-text-light);
    margin-bottom: 4px;
}

/* 詳細レイアウト */
.detail-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
}

@media (max-width: 1024px) {
    .detail-layout {
        grid-template-columns: 1fr;
    }

    .detail-sidebar {
        order: -1;
    }
}

/* お問い合わせメッセージ */
.contact-message__subject,
.contact-message__content {
    margin-bottom: 24px;
}

.contact-message__subject:last-child,
.contact-message__content:last-child {
    margin-bottom: 0;
}

.contact-message h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--admin-text);
    margin-bottom: 8px;
    padding-bottom: 4px;
    border-bottom: 1px solid var(--admin-border);
}

.contact-message__subject p {
    font-size: 16px;
    font-weight: 600;
    color: var(--admin-text);
    margin: 0;
}

.message-content {
    padding: 16px;
    background-color: #F8F9FA;
    border-left: 4px solid var(--admin-primary);
    border-radius: var(--admin-radius);
    line-height: 1.6;
    font-size: 14px;
    color: var(--admin-text);
    white-space: pre-wrap;
}

/* 返信内容 */
.reply-content__subject,
.reply-content__message {
    margin-bottom: 24px;
}

.reply-content__subject:last-child,
.reply-content__message:last-child {
    margin-bottom: 0;
}

.reply-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--admin-text);
    margin-bottom: 8px;
    padding-bottom: 4px;
    border-bottom: 1px solid var(--admin-border);
}

.reply-content__subject p {
    font-size: 16px;
    font-weight: 600;
    color: var(--admin-text);
    margin: 0;
}

/* お客様情報 */
.customer-info__item {
    margin-bottom: 16px;
}

.customer-info__item:last-child {
    margin-bottom: 0;
}

.customer-info__item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--admin-text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
}

.customer-info__item .value {
    font-size: 14px;
    color: var(--admin-text);
}

.customer-info__item .value a {
    color: var(--admin-primary);
    text-decoration: none;
}

.customer-info__item .value a:hover {
    text-decoration: underline;
}

/* 詳細情報 */
.detail-info__item {
    margin-bottom: 16px;
}

.detail-info__item:last-child {
    margin-bottom: 0;
}

.detail-info__item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--admin-text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
}

.detail-info__item .value {
    font-size: 13px;
    color: var(--admin-text);
    word-break: break-word;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .status-action-bar {
        flex-direction: column;
        gap: 16px;
    }

    .status-action-bar__right {
        align-self: stretch;
    }

    .status-action-bar__right .btn {
        flex: 1;
        text-align: center;
        min-width: 0;
    }

    .page-header {
        flex-direction: column;
        gap: 12px;
    }

    .page-header__right {
        align-self: stretch;
    }
}

/* ステータス */
.status--unread {
    background-color: var(--admin-warning);
    color: var(--admin-white);
}

.status--read {
    background-color: var(--admin-text-muted);
    color: var(--admin-white);
}

.status--replied {
    background-color: var(--admin-success);
    color: var(--admin-white);
}
</style>