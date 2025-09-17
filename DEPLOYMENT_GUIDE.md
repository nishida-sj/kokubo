# 🚀 サブドメインデプロイ手順書

小久保植樹園のサブドメインでの構築手順です。

## 📋 前提条件

- レンタルサーバーでサブドメインの作成が可能
- FTPアクセス権限がある
- MySQL データベースが使用可能
- PHP 8.0以上が利用可能

## 🌐 サブドメイン設定手順

### 1. サーバー側でサブドメイン作成

**設定完了済み:**
- サブドメイン名: `kokubosyokuju.geo.jp`
- URL: `http://kokubosyokuju.geo.jp/`
- ドキュメントルート: `/public_html/kokubosyokuju.geo.jp/`

### 2. GitHub Secrets 設定

GitHub リポジトリの Settings > Secrets and variables > Actions で以下を設定:

```
FTP_SERVER: あなたのFTPサーバー（例: ftp.example.com）
FTP_USERNAME: FTPユーザー名
FTP_PASSWORD: FTPパスワード
```

### 3. データベース作成

1. **データベース作成**
```sql
CREATE DATABASE kokubo_nursery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **ユーザー作成・権限付与**
```sql
CREATE USER 'kokubo_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON kokubo_nursery.* TO 'kokubo_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. 設定ファイルの調整

デプロイ後、サーバー上で `config/config.subdomain.php` を `config/config.php` にリネームし、以下を実際の値に変更:

```php
// URL設定（設定済み）
define('SITE_URL', 'http://kokubosyokuju.geo.jp');

// データベース設定（要変更）
define('DB_NAME', 'actual_db_name');
define('DB_USER', 'actual_db_user');
define('DB_PASS', 'actual_db_password');

// メール設定（設定済み）
define('MAIL_FROM', 'noreply@kokubosyokuju.geo.jp');
define('MAIL_TO', 'info@kokubosyokuju.geo.jp');

// セキュリティ（要変更）
define('PASSWORD_SALT', 'your_unique_production_salt');
```

### 5. デプロイ実行

1. **GitHub Actions有効化**
   - `.github/workflows/subdomain-deploy.yml` を確認
   - mainブランチにpushすると自動デプロイが開始されます

2. **手動デプロイ（必要に応じて）**
   - GitHub Actions の「Run workflow」ボタンをクリック

### 6. 初期セットアップ

デプロイ完了後、サーバー上で以下のコマンドを実行:

```bash
# サブドメインのルートディレクトリに移動
cd /public_html/kokubosyokuju.geo.jp

# データベース初期化
mysql -u username -p database_name < sql/schema.sql
mysql -u username -p database_name < sql/seed.sql

# 権限設定
chmod 755 public_html/uploads
mkdir -p public_html/uploads/works
chmod 755 public_html/uploads/works

# .htaccess設定
cp public_html/.htaccess-subdomain public_html/.htaccess

# Composer依存関係（サーバーでComposerが使える場合）
composer install --no-dev --optimize-autoloader
```

## 🔐 セキュリティ設定

### SSL証明書設定
1. cPanel > SSL/TLS で Let's Encrypt を有効化
2. または、サーバー管理者にSSL証明書の設定を依頼

### 管理画面アクセス
- URL: `https://kokubo.your-domain.com/admin`
- 初期ログイン:
  - ユーザー名: `admin`
  - パスワード: `admin123`

⚠️ **必ず本番運用前にパスワードを変更してください**

## 🧪 動作確認

### フロントエンド
- [ ] トップページ: `http://kokubosyokuju.geo.jp`
- [ ] 実績一覧: `http://kokubosyokuju.geo.jp/works`
- [ ] お問い合わせ: `http://kokubosyokuju.geo.jp/contact`

### 管理画面
- [ ] ログイン: `http://kokubosyokuju.geo.jp/admin`
- [ ] ダッシュボード表示
- [ ] 実績管理機能
- [ ] お問い合わせ管理機能

## 🔧 トラブルシューティング

### よくある問題

**1. 500 Internal Server Error**
- `config/config.php` の設定を確認
- ファイル権限を確認（755または644）
- PHPエラーログを確認

**2. データベース接続エラー**
- データベース設定（ホスト、ユーザー、パスワード）を確認
- データベースが作成されているか確認

**3. 画像アップロードエラー**
- `public_html/uploads` ディレクトリの権限を確認
- PHP の `upload_max_filesize` を確認

**4. .htaccess エラー**
- Apache の mod_rewrite が有効か確認
- .htaccess ファイルの構文を確認

## 📞 サポート

設定でご不明な点がございましたら、以下の情報と合わせてお問い合わせください：
- エラーメッセージ
- サーバー環境（cPanel、PHP バージョンなど）
- 設定した内容

---

© 2024 小久保植樹園 Deployment Guide