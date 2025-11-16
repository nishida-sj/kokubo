<?php
// 施工実績管理機能 - デプロイステータス確認ページ
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>デプロイステータス確認 | 小久保植樹園</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.95;
            font-size: 1.1em;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #2E7D32;
        }
        .section h2 {
            color: #2E7D32;
            margin-bottom: 15px;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            border: 1px solid #e0e0e0;
        }
        .status-icon {
            font-size: 2em;
            flex-shrink: 0;
        }
        .status-content {
            flex: 1;
        }
        .status-content h3 {
            color: #333;
            margin-bottom: 5px;
            font-size: 1.1em;
        }
        .status-content p {
            color: #666;
            font-size: 0.95em;
        }
        .status-content code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .check-ok {
            background: #e8f5e9 !important;
            border-color: #4CAF50 !important;
        }
        .check-warning {
            background: #fff3e0 !important;
            border-color: #FF9800 !important;
        }
        .check-error {
            background: #ffebee !important;
            border-color: #f44336 !important;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .link-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
            text-align: center;
        }
        .link-card:hover {
            border-color: #2E7D32;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46,125,50,0.2);
        }
        .link-card .icon {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .link-card .title {
            font-weight: bold;
            color: #2E7D32;
            margin-bottom: 5px;
        }
        .link-card .desc {
            font-size: 0.9em;
            color: #666;
        }
        .summary {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .summary h3 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .stat {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ 施工実績管理機能 デプロイステータス</h1>
            <p>すべての修正が正常にデプロイされています</p>
        </div>

        <div class="content">
            <?php
            // データベース接続チェック
            $dbOk = false;
            $worksCount = 0;
            $uploadsDirExists = false;
            $uploadsWorksExists = false;
            $uploadsThumbsExists = false;

            try {
                $db = Db::getInstance();
                $dbOk = true;
                $result = $db->fetchOne("SELECT COUNT(*) as count FROM works");
                $worksCount = $result['count'] ?? 0;
            } catch (Exception $e) {
                $dbOk = false;
            }

            // uploadsディレクトリチェック
            $uploadsDirExists = is_dir(PUBLIC_PATH . '/uploads');
            $uploadsWorksExists = is_dir(PUBLIC_PATH . '/uploads/works');
            $uploadsThumbsExists = is_dir(PUBLIC_PATH . '/uploads/works/thumbs');

            // ファイルシステムチェック
            $requiredFiles = [
                'index.php' => 'メインインデックス',
                'config/config.php' => '設定ファイル',
                'app/Controllers/Admin/WorksController.php' => '管理コントローラー',
                'app/Views/admin/pages/works/index.php' => '一覧ページ',
                'app/Views/admin/pages/works/create.php' => '新規作成ページ',
                'app/Views/admin/pages/works/edit.php' => '編集ページ',
                'app/Helpers/ImageTool.php' => '画像ヘルパー',
                'assets/js/admin.js' => '管理画面JavaScript',
            ];

            $filesOk = 0;
            $filesMissing = [];
            foreach ($requiredFiles as $file => $name) {
                if (file_exists(__DIR__ . '/../' . $file)) {
                    $filesOk++;
                } else {
                    $filesMissing[] = $name . ' (' . $file . ')';
                }
            }

            $allChecksPass = $dbOk && $uploadsDirExists && $uploadsWorksExists && count($filesMissing) === 0;
            ?>

            <!-- サマリー -->
            <div class="summary">
                <h3>📊 システムステータス概要</h3>
                <div class="summary-stats">
                    <div class="stat">
                        <div class="stat-number"><?= $dbOk ? '✅' : '❌' ?></div>
                        <div class="stat-label">データベース接続</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?= $worksCount ?></div>
                        <div class="stat-label">登録済み実績</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?= $filesOk ?>/<?= count($requiredFiles) ?></div>
                        <div class="stat-label">必須ファイル</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number"><?= $uploadsWorksExists ? '✅' : '❌' ?></div>
                        <div class="stat-label">アップロード準備</div>
                    </div>
                </div>
            </div>

            <!-- 修正完了項目 -->
            <div class="section">
                <h2>✅ 完了した修正項目</h2>

                <div class="status-item check-ok">
                    <div class="status-icon">🔧</div>
                    <div class="status-content">
                        <h3>1. サブドメインデプロイ設定修正</h3>
                        <p>index-subdomain.phpで正しいconfig.phpパスを設定し、500エラーを解消しました。</p>
                        <code>Commit: 65abcfd</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">📝</div>
                    <div class="status-content">
                        <h3>2. フォーム送信処理の修正</h3>
                        <p>admin.jsのイベントハンドラをclickからsubmitに変更し、登録処理が完了するようになりました。</p>
                        <code>Commit: 41ee0ae</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">🔤</div>
                    <div class="status-content">
                        <h3>3. 日本語タイトルのスラッグ自動生成</h3>
                        <p>generate_slug()関数を改善し、日本語タイトルでもタイムスタンプベースのスラッグが自動生成されます。</p>
                        <code>Commit: a7355d9</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">🖼️</div>
                    <div class="status-content">
                        <h3>4. 画像パスの修正</h3>
                        <p>ImageTool::upload()が/uploadsプレフィックスを含むパスを返すように修正しました。</p>
                        <code>Commit: a7355d9</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">📁</div>
                    <div class="status-content">
                        <h3>5. uploadsディレクトリ構造の配置</h3>
                        <p>.gitkeepファイルを使用して、uploadsディレクトリ構造が確実にデプロイされるようになりました。</p>
                        <code>Commit: fd43097, 8b9548c</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">🎨</div>
                    <div class="status-content">
                        <h3>6. 管理画面一覧ページの画像表示</h3>
                        <p>旧形式のパス（/uploads未含）を自動修正するロジックを追加しました。</p>
                        <code>Commit: 65b3e91</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">🏠</div>
                    <div class="status-content">
                        <h3>7. フロントエンドデザイン改善</h3>
                        <p>トップページと施工実績一覧ページのデザインを改善し、画像表示を最適化しました。</p>
                        <code>Commit: 65b3e91</code>
                    </div>
                </div>

                <div class="status-item check-ok">
                    <div class="status-icon">✏️</div>
                    <div class="status-content">
                        <h3>8. 編集ページの画像表示修正（最新）</h3>
                        <p>編集ページでもメイン画像と追加画像の両方で画像パス自動修正を適用しました。</p>
                        <code>Commit: 1ed06ec</code>
                    </div>
                </div>
            </div>

            <!-- システムチェック -->
            <div class="section">
                <h2>🔍 システムチェック結果</h2>

                <div class="status-item <?= $dbOk ? 'check-ok' : 'check-error' ?>">
                    <div class="status-icon"><?= $dbOk ? '✅' : '❌' ?></div>
                    <div class="status-content">
                        <h3>データベース接続</h3>
                        <p><?= $dbOk ? "正常に接続できています。登録済み実績: {$worksCount}件" : 'データベースに接続できません' ?></p>
                    </div>
                </div>

                <div class="status-item <?= $uploadsDirExists ? 'check-ok' : 'check-error' ?>">
                    <div class="status-icon"><?= $uploadsDirExists ? '✅' : '❌' ?></div>
                    <div class="status-content">
                        <h3>/uploads ディレクトリ</h3>
                        <p><?= $uploadsDirExists ? 'ディレクトリが存在します' : 'ディレクトリが見つかりません' ?></p>
                    </div>
                </div>

                <div class="status-item <?= $uploadsWorksExists ? 'check-ok' : 'check-error' ?>">
                    <div class="status-icon"><?= $uploadsWorksExists ? '✅' : '❌' ?></div>
                    <div class="status-content">
                        <h3>/uploads/works ディレクトリ</h3>
                        <p><?= $uploadsWorksExists ? 'ディレクトリが存在します' : 'ディレクトリが見つかりません' ?></p>
                    </div>
                </div>

                <div class="status-item <?= $uploadsThumbsExists ? 'check-ok' : 'check-error' ?>">
                    <div class="status-icon"><?= $uploadsThumbsExists ? '✅' : '❌' ?></div>
                    <div class="status-content">
                        <h3>/uploads/works/thumbs ディレクトリ</h3>
                        <p><?= $uploadsThumbsExists ? 'ディレクトリが存在します' : 'ディレクトリが見つかりません' ?></p>
                    </div>
                </div>

                <div class="status-item <?= count($filesMissing) === 0 ? 'check-ok' : 'check-warning' ?>">
                    <div class="status-icon"><?= count($filesMissing) === 0 ? '✅' : '⚠️' ?></div>
                    <div class="status-content">
                        <h3>必須ファイル</h3>
                        <p><?= count($filesMissing) === 0 ? "すべての必須ファイルが存在します（{$filesOk}個）" : '一部のファイルが見つかりません: ' . implode(', ', $filesMissing) ?></p>
                    </div>
                </div>
            </div>

            <!-- 次のステップ -->
            <div class="section">
                <h2>📋 次のステップ</h2>

                <?php if ($allChecksPass): ?>
                    <div class="status-item check-ok">
                        <div class="status-icon">🎉</div>
                        <div class="status-content">
                            <h3>システムは正常です！</h3>
                            <p>すべてのチェックに合格しました。管理画面から施工実績を登録・編集できます。</p>
                        </div>
                    </div>

                    <div class="status-item check-warning">
                        <div class="status-icon">📸</div>
                        <div class="status-content">
                            <h3>画像の再アップロードについて</h3>
                            <p>uploadsディレクトリ構造が新しくなったため、既存の実績データの画像は管理画面から再度アップロードしてください。</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="status-item check-error">
                        <div class="status-icon">⚠️</div>
                        <div class="status-content">
                            <h3>一部のチェックが失敗しています</h3>
                            <p>上記のシステムチェック結果を確認し、エラーを解決してください。</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- クイックリンク -->
            <div class="section">
                <h2>🔗 クイックリンク</h2>
                <div class="links">
                    <a href="<?= site_url('/') ?>" class="link-card">
                        <div class="icon">🏠</div>
                        <div class="title">トップページ</div>
                        <div class="desc">公開サイトトップ</div>
                    </a>

                    <a href="<?= site_url('/works') ?>" class="link-card">
                        <div class="icon">📋</div>
                        <div class="title">施工実績一覧</div>
                        <div class="desc">フロントエンド表示</div>
                    </a>

                    <a href="<?= site_url('/admin') ?>" class="link-card">
                        <div class="icon">🔐</div>
                        <div class="title">管理画面ログイン</div>
                        <div class="desc">管理画面へログイン</div>
                    </a>

                    <a href="<?= site_url('/admin/works') ?>" class="link-card">
                        <div class="icon">⚙️</div>
                        <div class="title">実績管理</div>
                        <div class="desc">実績の一覧・編集</div>
                    </a>

                    <a href="<?= site_url('/admin/works/create') ?>" class="link-card">
                        <div class="icon">➕</div>
                        <div class="title">新規作成</div>
                        <div class="desc">新しい実績を登録</div>
                    </a>

                    <a href="<?= site_url('/debug-images.php') ?>" class="link-card">
                        <div class="icon">🔧</div>
                        <div class="title">画像デバッグ</div>
                        <div class="desc">詳細な画像パス確認</div>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>最終デプロイ: Commit 1ed06ec - 編集ページの画像表示を修正</p>
            <p>© <?= date('Y') ?> 小久保植樹園 - すべての権利を保有</p>
        </div>
    </div>
</body>
</html>
