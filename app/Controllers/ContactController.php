<?php

class ContactController extends Controller
{
    public function index()
    {
        $html = '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 40px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; text-align: center; margin-bottom: 30px; }
        .nav { text-align: center; margin: 20px 0; }
        .nav a { margin: 0 15px; color: #2E7D32; text-decoration: none; font-weight: 500; }
        .nav a:hover { text-decoration: underline; }
        .form-group { margin: 20px 0; }
        label { display: block; margin-bottom: 5px; color: #333; font-weight: 500; }
        input[type="text"], input[type="email"], input[type="tel"], textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; box-sizing: border-box; }
        textarea { height: 120px; resize: vertical; }
        .btn { background: #2E7D32; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #1B5E20; }
        .contact-info { background: #f5f5f5; padding: 20px; border-radius: 4px; margin: 30px 0; }
        .info-item { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📞 お問い合わせ</h1>

        <div class="nav">
            <a href="/">ホーム</a>
            <a href="/works">施工実績</a>
            <a href="/contact">お問い合わせ</a>
        </div>

        <div class="contact-info">
            <h3>📍 お電話でのお問い合わせ</h3>
            <div class="info-item"><strong>電話:</strong> 0596-00-0000</div>
            <div class="info-item"><strong>営業時間:</strong> 平日 8:00-18:00 / 土曜 8:00-17:00</div>
            <div class="info-item"><strong>定休日:</strong> 日曜・祝日</div>
        </div>

        <form method="POST" action="/contact/send">
            <input type="hidden" name="csrf_token" value="' . csrf_token() . '">

            <div class="form-group">
                <label for="name">お名前 <span style="color:red;">*</span></label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス <span style="color:red;">*</span></label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">電話番号</label>
                <input type="tel" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="subject">件名</label>
                <input type="text" id="subject" name="subject" placeholder="例：庭園設計について">
            </div>

            <div class="form-group">
                <label for="message">お問い合わせ内容 <span style="color:red;">*</span></label>
                <textarea id="message" name="message" required placeholder="ご質問やご要望をお聞かせください"></textarea>
            </div>

            <button type="submit" class="btn">送信する</button>
        </form>

        <div class="contact-info" style="margin-top: 30px;">
            <h3>✉️ メールでのお問い合わせ</h3>
            <div class="info-item"><strong>メール:</strong> info@kokubosyokuju.geo.jp</div>
            <div class="info-item">24時間受付（返信は営業時間内）</div>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    public function send()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->index();
            }

            // CSRFトークン確認
            if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                throw new Exception('不正なリクエストです。');
            }

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';

            if (empty($name) || empty($email) || empty($message)) {
                throw new Exception('必須項目を入力してください。');
            }

            // データベースに保存
            $db = Database::getInstance();
            $db->insert('contacts', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);

            return '<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 | 小久保植樹園</title>
    <style>
        body { font-family: "Hiragino Sans", sans-serif; margin: 0; padding: 40px; background: #f8f9fa; }
        .container { max-width: 600px; margin: 100px auto; background: white; padding: 40px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2E7D32; margin-bottom: 20px; }
        .success { background: #e8f5e9; padding: 20px; border-radius: 4px; margin: 20px 0; color: #2e7d32; }
        .btn { background: #2E7D32; color: white; padding: 12px 30px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn:hover { background: #1B5E20; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ 送信完了</h1>
        <div class="success">
            <p>お問い合わせありがとうございます。</p>
            <p>内容を確認後、2営業日以内にご返信いたします。</p>
        </div>
        <a href="/" class="btn">ホームに戻る</a>
    </div>
</body>
</html>';

        } catch (Exception $e) {
            return '<h1>送信エラー</h1><p>' . htmlspecialchars($e->getMessage()) . '</p><p><a href="/contact">戻る</a></p>';
        }
    }
}