<?php
// 管理画面お問い合わせ一覧
?>

<!-- ページヘッダー -->
<div class="page-header">
    <div class="page-header__left">
        <h1 class="page-title">お問い合わせ管理</h1>
        <p class="page-description">お客様からのお問い合わせを管理できます</p>
    </div>
</div>

<!-- フィルター・検索 -->
<div class="card mb-3">
    <div class="card__content">
        <form action="<?= site_url('admin/contacts') ?>" method="GET" class="filter-form">
            <div class="filter-form__row">
                <!-- 検索 -->
                <div class="filter-form__group">
                    <label class="form-label">検索</label>
                    <input type="text"
                           name="q"
                           value="<?= h($filters['search']) ?>"
                           placeholder="名前、メール、件名、内容で検索..."
                           class="form-input">
                </div>

                <!-- ステータス -->
                <div class="filter-form__group">
                    <label class="form-label">ステータス</label>
                    <select name="status" class="form-select">
                        <option value="">すべて</option>
                        <option value="unread" <?= ($filters['status'] === 'unread') ? 'selected' : '' ?>>未読</option>
                        <option value="read" <?= ($filters['status'] === 'read') ? 'selected' : '' ?>>既読</option>
                        <option value="replied" <?= ($filters['status'] === 'replied') ? 'selected' : '' ?>>返信済み</option>
                    </select>
                </div>

                <!-- ソート -->
                <div class="filter-form__group">
                    <label class="form-label">並び順</label>
                    <select name="sort" class="form-select">
                        <option value="created_at" <?= ($filters['sort'] === 'created_at') ? 'selected' : '' ?>>受信日時</option>
                        <option value="name" <?= ($filters['sort'] === 'name') ? 'selected' : '' ?>>名前</option>
                        <option value="email" <?= ($filters['sort'] === 'email') ? 'selected' : '' ?>>メールアドレス</option>
                        <option value="subject" <?= ($filters['sort'] === 'subject') ? 'selected' : '' ?>>件名</option>
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

<!-- お問い合わせリスト -->
<div class="card">
    <div class="card__header">
        <h3 class="card__title">
            お問い合わせ一覧
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

    <?php if (!empty($contacts)): ?>
        <div class="card__content">
            <div class="table-responsive">
                <table class="table table--hover">
                    <thead>
                        <tr>
                            <th width="80">ステータス</th>
                            <th>名前・会社</th>
                            <th>件名</th>
                            <th width="120">受信日時</th>
                            <th width="150">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr class="<?= !$contact['is_read'] ? 'is-unread' : '' ?>">
                                <td>
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
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <h4 class="contact-info__name">
                                            <a href="<?= site_url('admin/contacts/' . $contact['id']) ?>"
                                               class="contact-info__link">
                                                <?= h($contact['name']) ?>
                                            </a>
                                        </h4>
                                        <div class="contact-info__email">
                                            📧 <?= h($contact['email']) ?>
                                        </div>
                                        <?php if (!empty($contact['phone'])): ?>
                                            <div class="contact-info__phone">
                                                📞 <?= h($contact['phone']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-subject">
                                        <h5 class="contact-subject__text">
                                            <?= h($contact['subject']) ?>
                                        </h5>
                                        <div class="contact-subject__preview">
                                            <?= h(truncate_text($contact['message'], 80)) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-small text-muted">
                                    <?= format_date($contact['created_at'], 'Y/m/d H:i') ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= site_url('admin/contacts/' . $contact['id']) ?>"
                                           class="action-btn action-btn--view"
                                           title="詳細表示">
                                            👁
                                        </a>
                                        <a href="mailto:<?= h($contact['email']) ?>?subject=Re: <?= urlencode($contact['subject']) ?>&body=<?= urlencode($contact['name'] . ' 様' . "\n\n" . 'この度は、お問い合わせいただきありがとうございます。' . "\n\n" . '■ お問い合わせ内容' . "\n" . $contact['message'] . "\n\n" . '■ 回答内容' . "\n\n") ?>"
                                           class="action-btn action-btn--reply"
                                           title="メール返信">
                                            📧
                                        </a>
                                        <?php if (!$contact['is_read']): ?>
                                            <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/mark-read') ?>"
                                               class="action-btn action-btn--mark-read"
                                               title="既読にする">
                                                ✓
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/mark-unread') ?>"
                                               class="action-btn action-btn--mark-unread"
                                               title="未読にする">
                                                ✗
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= site_url('admin/contacts/' . $contact['id'] . '/delete') ?>"
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
                        <a href="<?= site_url('admin/contacts?' . http_build_query($prevParams)) ?>"
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
                            <a href="<?= site_url('admin/contacts?' . http_build_query($pageParams)) ?>"
                               class="pagination__link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagination['hasNext']): ?>
                        <?php
                        $nextParams = $_GET;
                        $nextParams['page'] = $pagination['page'] + 1;
                        ?>
                        <a href="<?= site_url('admin/contacts?' . http_build_query($nextParams)) ?>"
                           class="pagination__link">次へ</a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card__content">
            <div class="empty-state">
                <div class="empty-state__icon">📬</div>
                <h3 class="empty-state__title">お問い合わせがありません</h3>
                <p class="empty-state__text">
                    <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                        検索条件に一致するお問い合わせが見つかりませんでした。<br>
                        <a href="<?= site_url('admin/contacts') ?>" class="link">すべてのお問い合わせを表示</a>
                    <?php else: ?>
                        まだお問い合わせが届いていません。
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* お問い合わせ管理一覧ページ専用スタイル */

/* フィルターフォーム */
.filter-form__row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
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
}

.filter-form__group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* 未読行のハイライト */
.table tr.is-unread {
    background-color: #FFF3E0;
}

.table tr.is-unread:hover {
    background-color: #FFE0B2;
}

/* お問い合わせ情報 */
.contact-info__name {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.contact-info__link {
    color: var(--admin-text);
    text-decoration: none;
}

.contact-info__link:hover {
    color: var(--admin-primary);
}

.contact-info__email,
.contact-info__phone {
    font-size: 12px;
    color: var(--admin-text-light);
    margin-bottom: 2px;
}

/* 件名・内容 */
.contact-subject__text {
    margin: 0 0 4px 0;
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
}

.contact-subject__preview {
    font-size: 12px;
    color: var(--admin-text-muted);
    line-height: 1.4;
}

/* ステータス */
.status-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

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

/* アクションボタン */
.action-btn--reply {
    background-color: var(--admin-success);
    color: var(--admin-white);
}

.action-btn--reply:hover {
    background-color: #388E3C;
}

.action-btn--mark-read {
    background-color: var(--admin-info);
    color: var(--admin-white);
}

.action-btn--mark-read:hover {
    background-color: #1976D2;
}

.action-btn--mark-unread {
    background-color: var(--admin-warning);
    color: var(--admin-white);
}

.action-btn--mark-unread:hover {
    background-color: #F57C00;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }

    .table th,
    .table td {
        padding: 8px 4px;
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

    .contact-info__name {
        font-size: 13px;
    }

    .contact-info__email,
    .contact-info__phone {
        font-size: 11px;
    }

    .contact-subject__text {
        font-size: 12px;
    }

    .contact-subject__preview {
        font-size: 11px;
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
}

.link {
    color: var(--admin-primary);
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

/* カウント */
.count {
    font-size: 14px;
    font-weight: 400;
    color: var(--admin-text-light);
}
</style>