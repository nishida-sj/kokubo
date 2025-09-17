# 小久保植樹園 - 企業ウェブサイト

三重県伊勢市の植樹園「小久保植樹園」の企業ウェブサイトです。

## 🏗 プロジェクト概要

### 技術スタック
- **フロントエンド**: HTML5, CSS3, JavaScript (Vanilla)
- **バックエンド**: PHP 8.0+
- **データベース**: MySQL 8.0+
- **デプロイ**: GitHub Actions

### 主な機能
- ✅ レスポンシブランディングページ
- ✅ 植栽・造園実績管理（CRUD）
- ✅ お問い合わせフォーム
- ✅ 管理画面
- ✅ SEO最適化
- ✅ サイトマップ自動生成
- ✅ 画像アップロード・リサイズ
- ✅ メール送信機能

## 📋 機能詳細

### 公開側（フロントエンド）
- **ランディングページ** (`/`)
  - ヒーロー、特徴、サービス、植栽・造園実績、お客様の声、お問い合わせCTA
- **植栽・造園実績** (`/works`)
  - 一覧表示、カテゴリーフィルター、詳細ページ
- **お問い合わせ** (`/contact`)
  - フォーム送信、DB保存、メール送信

### 管理画面（バックエンド）
- **ログイン・認証** (`/admin`)
- **ダッシュボード** - 統計情報と最新活動
- **植栽・造園実績管理** - CRUD操作、画像アップロード
- **お問い合わせ管理** - 返信機能、ステータス管理
- **サイト設定** - 会社情報、SEO設定

### SEO・技術仕様
- 構造化データ（JSON-LD）
- OGP・Twitter Card対応
- sitemap.xml自動生成
- robots.txt設定
- レスポンシブデザイン
- セキュリティ対策（CSRF、XSS、SQLインジェクション）

## 🚀 セットアップ

### 必要要件
- PHP 8.0以上
- MySQL 8.0以上
- Composer
- Webサーバー（Apache/Nginx）

### インストール手順

1. **リポジトリのクローン**
```bash
git clone https://github.com/your-username/kokubo-nursery.git
cd kokubo-nursery
```

2. **依存関係のインストール**
```bash
composer install
```

3. **データベース設定**
```bash
# データベース作成
mysql -u root -p -e "CREATE DATABASE kokubo_nursery;"

# スキーマの実行
mysql -u root -p kokubo_nursery < sql/schema.sql

# サンプルデータの投入（オプション）
mysql -u root -p kokubo_nursery < sql/seed.sql
```

4. **設定ファイルの作成**
```bash
cp config/config.php.example config/config.php
```

`config/config.php`を編集してデータベース接続情報を設定してください。

5. **権限設定**
```bash
chmod 755 public/uploads
chmod 755 public/uploads/works
```

6. **Webサーバー設定**

Apache使用時は、`public/.htaccess`が正しく動作するようにmod_rewriteを有効化してください。

## 📁 ディレクトリ構成

```
kokubo/
├── app/
│   ├── Controllers/          # コントローラー
│   │   ├── Admin/           # 管理画面コントローラー
│   │   ├── HomeController.php
│   │   ├── WorksController.php
│   │   └── ContactController.php
│   ├── Helpers/             # ヘルパークラス
│   │   ├── Auth.php
│   │   ├── Csrf.php
│   │   ├── Db.php
│   │   ├── ImageTool.php
│   │   ├── Seo.php
│   │   ├── Schema.php
│   │   └── Validator.php
│   ├── Views/               # ビューファイル
│   │   ├── admin/           # 管理画面テンプレート
│   │   ├── layouts/         # レイアウトファイル
│   │   └── pages/           # ページテンプレート
│   └── Router.php           # ルーター
├── assets/
│   ├── css/                 # スタイルシート
│   ├── js/                  # JavaScript
│   └── img/                 # 画像ファイル
├── config/
│   └── config.php           # 設定ファイル
├── public/
│   ├── uploads/             # アップロードファイル
│   ├── .htaccess           # Apache設定
│   └── index.php           # エントリーポイント
├── sql/
│   ├── schema.sql          # データベーススキーマ
│   └── seed.sql            # サンプルデータ
└── README.md
```

## 🔧 開発

### 管理画面アクセス
- URL: `/admin`
- デフォルトログイン:
  - ユーザー名: `admin`
  - パスワード: `admin123`

### 新しいページの追加
1. `app/Controllers/`にコントローラーを作成
2. `app/Views/pages/`にビューファイルを作成
3. `public/index.php`にルートを追加

### データベースマイグレーション
スキーマの変更は`sql/schema.sql`を更新してください。

## 🎨 デザインシステム

### カラーパレット（伊勢市・植樹園テーマ）
- プライマリー: `#2E7D32` (森の緑)
- セカンダリー: `#8D6E63` (大地のブラウン)
- アクセント: `#FF7043` (花のオレンジ)
- 背景: `#F5F5F5` (和紙ホワイト)

### レスポンシブブレイクポイント
- モバイル: `< 768px`
- タブレット: `768px - 1024px`
- デスクトップ: `> 1024px`

## 🔒 セキュリティ機能

- CSRF保護
- XSS対策
- SQLインジェクション対策
- セッション管理
- ファイルアップロード制限
- 管理画面アクセス制御

## 📈 SEO対策

- 構造化データ（JSON-LD）
- サイトマップ自動生成
- メタタグ最適化
- Open Graph対応
- robots.txt設定

## 🚀 デプロイ

GitHub Actionsを使用した自動デプロイが設定されています。

### 本番環境設定

1. **シークレット変数の設定**（GitHub Settings > Secrets）:
   - `DEPLOY_HOST`: サーバーホスト
   - `DEPLOY_USER`: SSH ユーザー名
   - `DEPLOY_KEY`: SSH秘密鍵
   - `DEPLOY_PATH`: デプロイパス

2. **本番用設定ファイル**
```php
// config/config.php（本番環境）
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_production_nursery_db');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_secure_password');
define('APP_ENV', 'production');
```

## 📞 サポート

### お問い合わせ
- 開発者: [Your Name]
- メール: [your-email@example.com]

### バグレポート
GitHubのIssuesからお願いします。

## 📄 ライセンス

MIT License - 詳細は[LICENSE](LICENSE)ファイルをご覧ください。

---

© 2024 小久保植樹園. All rights reserved.