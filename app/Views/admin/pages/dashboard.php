<?php
// 管理画面ダッシュボード
?>

<!-- 統計カード -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--primary">🏠</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['total_works']) ?></div>
            <div class="stat-card__label">総実績数</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--success">✓</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['published_works']) ?></div>
            <div class="stat-card__label">公開済み実績</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--warning">⭐</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['featured_works']) ?></div>
            <div class="stat-card__label">おすすめ実績</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--info">✉</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['total_contacts']) ?></div>
            <div class="stat-card__label">総お問い合わせ</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--error">🔔</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['unread_contacts']) ?></div>
            <div class="stat-card__label">未読お問い合わせ</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--secondary">📁</div>
        <div class="stat-card__content">
            <div class="stat-card__number"><?= number_format($stats['total_categories']) ?></div>
            <div class="stat-card__label">カテゴリー数</div>
        </div>
    </div>
</div>

<!-- メインコンテンツ -->
<div class="dashboard-content">
    <div class="dashboard-left">
        <!-- 最近の実績 -->
        <div class="card mb-3">
            <div class="card__header">
                <h3 class="card__title">最近の実績</h3>
                <a href="<?= site_url('admin/works') ?>" class="btn btn--outline btn--small">すべて見る</a>
            </div>
            <div class="card__content">
                <?php if (!empty($recentWorks)): ?>
                    <div class="recent-items">
                        <?php foreach ($recentWorks as $work): ?>
                            <div class="recent-item">
                                <div class="recent-item__image">
                                    <?php if (!empty($work['main_image'])): ?>
                                        <img src="<?= site_url($work['main_image']) ?>"
                                             alt="<?= h($work['title']) ?>"
                                             class="recent-item__img">
                                    <?php else: ?>
                                        <div class="recent-item__placeholder">🏠</div>
                                    <?php endif; ?>
                                </div>
                                <div class="recent-item__content">
                                    <h4 class="recent-item__title">
                                        <a href="<?= site_url('admin/works/' . $work['id'] . '/edit') ?>">
                                            <?= h($work['title']) ?>
                                        </a>
                                    </h4>
                                    <div class="recent-item__meta">
                                        <span class="recent-item__category"><?= h($work['category_name']) ?></span>
                                        <span class="recent-item__date"><?= format_date($work['created_at'], 'm/d') ?></span>
                                    </div>
                                    <div class="recent-item__status">
                                        <?php if ($work['is_published']): ?>
                                            <span class="status status--published">公開中</span>
                                        <?php else: ?>
                                            <span class="status status--draft">下書き</span>
                                        <?php endif; ?>
                                        <?php if ($work['is_featured']): ?>
                                            <span class="status status--featured">おすすめ</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p class="empty-state__text">まだ実績が登録されていません。</p>
                        <a href="<?= site_url('admin/works/create') ?>" class="btn btn--primary">最初の実績を追加</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 月別統計 -->
        <?php if (!empty($monthlyStats)): ?>
            <div class="card">
                <div class="card__header">
                    <h3 class="card__title">月別実績投稿数</h3>
                </div>
                <div class="card__content">
                    <div class="chart-container">
                        <canvas id="monthlyChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-right">
        <!-- 最近のお問い合わせ -->
        <div class="card mb-3">
            <div class="card__header">
                <h3 class="card__title">最近のお問い合わせ</h3>
                <a href="<?= site_url('admin/contacts') ?>" class="btn btn--outline btn--small">すべて見る</a>
            </div>
            <div class="card__content">
                <?php if (!empty($recentContacts)): ?>
                    <div class="contact-list">
                        <?php foreach ($recentContacts as $contact): ?>
                            <div class="contact-item <?= !$contact['is_read'] ? 'contact-item--unread' : '' ?>">
                                <div class="contact-item__header">
                                    <h4 class="contact-item__name"><?= h($contact['name']) ?></h4>
                                    <span class="contact-item__date"><?= format_date($contact['created_at'], 'm/d H:i') ?></span>
                                </div>
                                <div class="contact-item__subject">
                                    <?= h($contact['subject'] ?: 'お問い合わせ') ?>
                                </div>
                                <div class="contact-item__message">
                                    <?= h(truncate_text($contact['message'], 80)) ?>
                                </div>
                                <div class="contact-item__actions">
                                    <a href="<?= site_url('admin/contacts/' . $contact['id']) ?>" class="btn btn--small btn--outline">
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p class="empty-state__text">お問い合わせはまだありません。</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- カテゴリー別統計 -->
        <?php if (!empty($categoryStats)): ?>
            <div class="card">
                <div class="card__header">
                    <h3 class="card__title">カテゴリー別実績数</h3>
                </div>
                <div class="card__content">
                    <div class="category-stats">
                        <?php foreach ($categoryStats as $category): ?>
                            <div class="category-stat">
                                <div class="category-stat__name"><?= h($category['name']) ?></div>
                                <div class="category-stat__bar">
                                    <div class="category-stat__fill"
                                         style="width: <?= $categoryStats[0]['count'] > 0 ? ($category['count'] / $categoryStats[0]['count'] * 100) : 0 ?>%"></div>
                                </div>
                                <div class="category-stat__count"><?= $category['count'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* ダッシュボード専用スタイル */

/* 統計カード */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--admin-white);
    border-radius: var(--admin-radius);
    box-shadow: var(--admin-shadow);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-card__icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.stat-card__icon--primary { background-color: var(--admin-primary); color: var(--admin-white); }
.stat-card__icon--success { background-color: var(--admin-success); color: var(--admin-white); }
.stat-card__icon--warning { background-color: var(--admin-warning); color: var(--admin-white); }
.stat-card__icon--error { background-color: var(--admin-error); color: var(--admin-white); }
.stat-card__icon--info { background-color: var(--admin-info); color: var(--admin-white); }
.stat-card__icon--secondary { background-color: var(--admin-secondary); color: var(--admin-white); }

.stat-card__number {
    font-size: 28px;
    font-weight: 700;
    color: var(--admin-text);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-card__label {
    font-size: 14px;
    color: var(--admin-text-light);
}

/* ダッシュボードレイアウト */
.dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

@media (max-width: 1024px) {
    .dashboard-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

/* 最近のアイテム */
.recent-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.recent-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    border: 1px solid var(--admin-border);
    border-radius: var(--admin-radius);
    transition: all var(--admin-transition);
}

.recent-item:hover {
    background-color: #FAFAFA;
}

.recent-item__image {
    width: 60px;
    height: 60px;
    border-radius: var(--admin-radius);
    overflow: hidden;
    flex-shrink: 0;
}

.recent-item__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-item__placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--admin-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.recent-item__content {
    flex: 1;
    min-width: 0;
}

.recent-item__title {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.recent-item__title a {
    color: var(--admin-text);
    text-decoration: none;
}

.recent-item__title a:hover {
    color: var(--admin-primary);
}

.recent-item__meta {
    display: flex;
    gap: 8px;
    font-size: 12px;
    color: var(--admin-text-light);
    margin-bottom: 6px;
}

.recent-item__status {
    display: flex;
    gap: 4px;
}

/* お問い合わせリスト */
.contact-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-item {
    padding: 12px;
    border: 1px solid var(--admin-border);
    border-radius: var(--admin-radius);
    background-color: var(--admin-white);
}

.contact-item--unread {
    border-left: 4px solid var(--admin-primary);
    background-color: #F3F8FF;
}

.contact-item__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.contact-item__name {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
    color: var(--admin-text);
}

.contact-item__date {
    font-size: 12px;
    color: var(--admin-text-light);
}

.contact-item__subject {
    font-size: 13px;
    font-weight: 500;
    color: var(--admin-text);
    margin-bottom: 4px;
}

.contact-item__message {
    font-size: 12px;
    color: var(--admin-text-light);
    line-height: 1.4;
    margin-bottom: 8px;
}

.contact-item__actions {
    text-align: right;
}

/* カテゴリー統計 */
.category-stats {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.category-stat {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-stat__name {
    flex: 0 0 100px;
    font-size: 13px;
    color: var(--admin-text);
}

.category-stat__bar {
    flex: 1;
    height: 8px;
    background-color: var(--admin-bg);
    border-radius: 4px;
    overflow: hidden;
}

.category-stat__fill {
    height: 100%;
    background-color: var(--admin-primary);
    transition: width var(--admin-transition);
}

.category-stat__count {
    flex: 0 0 30px;
    text-align: right;
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
}

/* 空状態 */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--admin-text-light);
}

.empty-state__text {
    margin-bottom: 16px;
    font-size: 14px;
}

/* チャート */
.chart-container {
    position: relative;
    height: 200px;
}

/* バッジ */
.badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    line-height: 1;
    margin-left: 6px;
}

.badge--error {
    background-color: var(--admin-error);
    color: var(--admin-white);
}

/* レスポンシブ */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .stat-card {
        padding: 16px;
        flex-direction: column;
        text-align: center;
    }

    .stat-card__icon {
        margin-bottom: 8px;
    }

    .recent-item {
        flex-direction: column;
    }

    .recent-item__image {
        width: 100%;
        height: 120px;
    }

    .category-stat__name {
        flex: 0 0 80px;
        font-size: 12px;
    }
}
</style>

<?php
// チャート用のJavaScriptデータを準備
if (!empty($monthlyStats)) {
    $chartData = [
        'labels' => array_map(function($stat) {
            return date('Y/m', strtotime($stat['month'] . '-01'));
        }, $monthlyStats),
        'data' => array_map(function($stat) {
            return (int)$stat['count'];
        }, $monthlyStats)
    ];
}
?>

<?php if (!empty($monthlyStats)): ?>
<script>
// 簡易チャート描画（Chart.jsの代わりの簡単な実装）
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('monthlyChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const data = <?= json_encode($chartData) ?>;

    // シンプルな棒グラフを描画
    function drawChart() {
        const width = canvas.width;
        const height = canvas.height;
        const padding = 40;
        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        // 背景をクリア
        ctx.clearRect(0, 0, width, height);

        // 最大値を取得
        const maxValue = Math.max(...data.data);
        if (maxValue === 0) return;

        // 棒グラフを描画
        const barWidth = chartWidth / data.data.length;

        ctx.fillStyle = '#1976D2';
        data.data.forEach((value, index) => {
            const barHeight = (value / maxValue) * chartHeight;
            const x = padding + index * barWidth + barWidth * 0.1;
            const y = height - padding - barHeight;
            const width = barWidth * 0.8;

            ctx.fillRect(x, y, width, barHeight);

            // 値を表示
            ctx.fillStyle = '#666';
            ctx.font = '12px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(value, x + width / 2, y - 5);
            ctx.fillStyle = '#1976D2';
        });

        // ラベルを描画
        ctx.fillStyle = '#666';
        ctx.font = '10px sans-serif';
        ctx.textAlign = 'center';
        data.labels.forEach((label, index) => {
            const x = padding + index * barWidth + barWidth / 2;
            const y = height - 15;
            ctx.fillText(label, x, y);
        });
    }

    drawChart();
});
</script>
<?php endif; ?>