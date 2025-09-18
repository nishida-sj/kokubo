<?php
// お問い合わせコントローラー

class ContactController
{
    public function index()
    {
        // SEO設定
        $seo = Seo::createForContact();

        // 構造化データ
        $schemas = [
            Schema::contactPage()
        ];

        // パンくずリスト
        $breadcrumbs = [
            ['name' => 'ホーム', 'url' => ''],
            ['name' => 'お問い合わせ']
        ];

        $schemas[] = Schema::breadcrumb($breadcrumbs);
        $schema = implode("\n", $schemas);

        // ビューに渡すデータ
        $data = [
            'page' => 'contact/index',
            'seo' => $seo,
            'schema' => $schema,
            'bodyClass' => 'page-contact'
        ];

        return $this->render($data);
    }

    public function send()
    {
        // POSTリクエストでない場合はリダイレクト
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('contact');
            return;
        }

        $db = Db::getInstance();
        $errors = [];
        $success = false;

        try {
            // CSRFトークン検証
            Csrf::requireToken();

            // 入力データを取得・サニタイズ
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'subject' => trim($_POST['subject'] ?? ''),
                'message' => trim($_POST['message'] ?? ''),
            ];

            // バリデーション
            $validator = new Validator($data);
            $isValid = $validator->validate([
                'name' => 'required|max:100',
                'email' => 'required|email|max:200',
                'phone' => 'phone|max:50',
                'address' => 'max:500',
                'subject' => 'max:200',
                'message' => 'required|max:5000',
            ]);

            if (!$isValid) {
                $errors = $validator->getErrors();
            }

            // エラーがない場合は保存・送信処理
            if (empty($errors)) {
                // データベースに保存
                $contactData = array_merge($data, [
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'is_read' => 0
                ]);

                $contactId = $db->insert('contacts', $contactData);

                // メール送信
                $this->sendContactEmail($data, $contactId);
                $this->sendAutoReplyEmail($data);

                $success = true;

                // CSRF トークンを更新
                Csrf::refreshToken();
            }

        } catch (Exception $e) {
            error_log('Contact form error: ' . $e->getMessage());
            $errors['general'] = ['システムエラーが発生しました。しばらくしてからもう一度お試しください。'];
        }

        // Ajax リクエストの場合はJSON形式で返す
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

            header('Content-Type: application/json');

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'お問い合わせを受け付けました。お返事まで今しばらくお待ちください。'
                ]);
            } else {
                $flatErrors = [];
                foreach ($errors as $fieldErrors) {
                    if (is_array($fieldErrors)) {
                        $flatErrors = array_merge($flatErrors, $fieldErrors);
                    } else {
                        $flatErrors[] = $fieldErrors;
                    }
                }

                echo json_encode([
                    'success' => false,
                    'errors' => $flatErrors
                ]);
            }
            return;
        }

        // 通常のリクエストの場合はページ表示
        $seo = Seo::createForContact();

        if ($success) {
            $seo->setTitle('お問い合わせ完了');
        }

        $data = [
            'page' => 'contact/index',
            'seo' => $seo,
            'bodyClass' => 'page-contact',
            'success' => $success,
            'errors' => $errors,
            'formData' => $success ? [] : $data
        ];

        return $this->render($data);
    }

    private function sendContactEmail($data, $contactId)
    {
        $to = MAIL_TO;
        $subject = '[' . APP_NAME . '] お問い合わせを受け付けました';

        $message = $this->buildContactEmailMessage($data, $contactId);

        $headers = [
            'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
            'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . phpversion()
        ];

        if (MAIL_USE_PHPMAILER) {
            // PHPMailer使用（実装する場合）
            $this->sendWithPHPMailer($to, $subject, $message, $headers);
        } else {
            // 標準のmail()関数を使用
            mail($to, $subject, $message, implode("\r\n", $headers));
        }
    }

    private function sendAutoReplyEmail($data)
    {
        $to = $data['email'];
        $subject = '[' . APP_NAME . '] お問い合わせありがとうございます';

        $message = $this->buildAutoReplyMessage($data);

        $headers = [
            'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . phpversion()
        ];

        if (MAIL_USE_PHPMAILER) {
            $this->sendWithPHPMailer($to, $subject, $message, $headers);
        } else {
            mail($to, $subject, $message, implode("\r\n", $headers));
        }
    }

    private function buildContactEmailMessage($data, $contactId)
    {
        $message = APP_NAME . "のWebサイトからお問い合わせがありました。\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "■ お問い合わせ情報\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "お問い合わせID: {$contactId}\n";
        $message .= "受付日時: " . date('Y年m月d日 H:i:s') . "\n\n";

        $message .= "【お名前】\n";
        $message .= $data['name'] . "\n\n";

        $message .= "【メールアドレス】\n";
        $message .= $data['email'] . "\n\n";

        if (!empty($data['phone'])) {
            $message .= "【電話番号】\n";
            $message .= $data['phone'] . "\n\n";
        }

        if (!empty($data['address'])) {
            $message .= "【ご住所】\n";
            $message .= $data['address'] . "\n\n";
        }

        if (!empty($data['subject'])) {
            $message .= "【件名】\n";
            $message .= $data['subject'] . "\n\n";
        }

        $message .= "【お問い合わせ内容】\n";
        $message .= $data['message'] . "\n\n";

        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "このメールは " . SITE_URL . " から自動送信されています。\n";
        $message .= "管理画面: " . site_url('admin') . "\n";

        return $message;
    }

    private function buildAutoReplyMessage($data)
    {
        $message = $data['name'] . " 様\n\n";
        $message .= "この度は、" . APP_NAME . "にお問い合わせいただき、ありがとうございます。\n";
        $message .= "以下の内容でお問い合わせを受け付けいたしました。\n\n";

        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "■ お問い合わせ内容\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $message .= "【お名前】\n";
        $message .= $data['name'] . "\n\n";

        $message .= "【メールアドレス】\n";
        $message .= $data['email'] . "\n\n";

        if (!empty($data['phone'])) {
            $message .= "【電話番号】\n";
            $message .= $data['phone'] . "\n\n";
        }

        if (!empty($data['subject'])) {
            $message .= "【件名】\n";
            $message .= $data['subject'] . "\n\n";
        }

        $message .= "【お問い合わせ内容】\n";
        $message .= $data['message'] . "\n\n";

        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "内容を確認の上、3営業日以内にご返信させていただきます。\n";
        $message .= "お急ぎの場合は、お電話にてお問い合わせください。\n\n";

        $db = Db::getInstance();
        $phone = $db->fetch("SELECT value FROM site_settings WHERE `key` = 'company_phone'")['value'] ?? '0596-00-0000';
        $hours = $db->fetch("SELECT value FROM site_settings WHERE `key` = 'business_hours'")['value'] ?? '平日 8:00-18:00 / 土曜 8:00-17:00';

        $message .= "【お電話でのお問い合わせ】\n";
        $message .= "TEL: {$phone}\n";
        $message .= "営業時間: {$hours}\n\n";

        $message .= "今後ともよろしくお願いいたします。\n\n";
        $message .= "─────────────────────────────\n";
        $message .= APP_NAME . "\n";
        $message .= "〒516-0000 三重県伊勢市○○町○○番地\n";
        $message .= "TEL: {$phone}\n";
        $message .= "Web: " . SITE_URL . "\n";
        $message .= "─────────────────────────────\n\n";

        $message .= "※このメールは自動送信されています。\n";
        $message .= "※このメールに心当たりがない場合は、お手数ですが削除をお願いいたします。\n";

        return $message;
    }

    private function sendWithPHPMailer($to, $subject, $message, $headers)
    {
        // PHPMailerライブラリが必要な場合の実装
        // 今回は簡易実装のため、標準のmail()関数で代用
        mail($to, $subject, $message, implode("\r\n", $headers));
    }

    private function render($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/layouts/base.php';
        return ob_get_clean();
    }
}