<?php
// 管理画面お問い合わせ管理コントローラー

class Admin_ContactsController
{
    public function index()
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // フィルター・検索パラメータ取得
        $status = $_GET['status'] ?? '';
        $search = $_GET['q'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at';
        $order = $_GET['order'] ?? 'desc';
        $page = max(1, (int)($_GET['page'] ?? 1));

        // SQLクエリ構築
        $whereConditions = ['1=1'];
        $params = [];

        if ($status === 'read') {
            $whereConditions[] = 'is_read = 1';
        } elseif ($status === 'unread') {
            $whereConditions[] = 'is_read = 0';
        } elseif ($status === 'replied') {
            $whereConditions[] = 'is_replied = 1';
        }

        if (!empty($search)) {
            $whereConditions[] = '(name LIKE :search OR email LIKE :search OR company LIKE :search OR subject LIKE :search OR message LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $whereClause = implode(' AND ', $whereConditions);

        // ソート設定
        $allowedSorts = ['name', 'email', 'company', 'subject', 'is_read', 'is_replied', 'created_at', 'updated_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        if (!in_array($order, ['asc', 'desc'])) $order = 'desc';

        $sql = "
            SELECT *
            FROM contacts
            WHERE {$whereClause}
            ORDER BY {$sort} {$order}
        ";

        $pagination = $db->getPagination($sql, $params, $page, ADMIN_ITEMS_PER_PAGE);

        $data = [
            'page' => 'admin/contacts/index',
            'title' => 'お問い合わせ管理',
            'contacts' => $pagination['data'],
            'pagination' => $pagination,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'sort' => $sort,
                'order' => $order
            ]
        ];

        return $this->renderAdmin($data);
    }

    public function show($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // お問い合わせ取得
        $contact = $db->fetch("SELECT * FROM contacts WHERE id = :id", ['id' => $id]);
        if (!$contact) {
            redirect('admin/contacts');
            return;
        }

        // 未読の場合は既読にする
        if (!$contact['is_read']) {
            $db->update('contacts', ['is_read' => 1], 'id = :id', ['id' => $id]);
            $contact['is_read'] = 1;
        }

        $data = [
            'page' => 'admin/contacts/show',
            'title' => 'お問い合わせ詳細',
            'contact' => $contact
        ];

        return $this->renderAdmin($data);
    }

    public function reply($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // お問い合わせ存在確認
        $contact = $db->fetch("SELECT * FROM contacts WHERE id = :id", ['id' => $id]);
        if (!$contact) {
            redirect('admin/contacts');
            return;
        }

        $data = [
            'page' => 'admin/contacts/reply',
            'title' => 'お問い合わせ返信',
            'contact' => $contact
        ];

        return $this->renderAdmin($data);
    }

    public function sendReply($id)
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/contacts');
            return;
        }

        $db = Db::getInstance();

        // お問い合わせ存在確認
        $contact = $db->fetch("SELECT * FROM contacts WHERE id = :id", ['id' => $id]);
        if (!$contact) {
            redirect('admin/contacts');
            return;
        }

        $errors = [];

        try {
            Csrf::requireToken();

            // 入力データ取得
            $replySubject = trim($_POST['reply_subject'] ?? '');
            $replyMessage = trim($_POST['reply_message'] ?? '');

            // バリデーション
            if (empty($replySubject)) {
                $errors['reply_subject'] = ['件名を入力してください。'];
            }

            if (empty($replyMessage)) {
                $errors['reply_message'] = ['返信内容を入力してください。'];
            }

            if (empty($errors)) {
                // メール送信
                $mail = new PHPMailer(true);

                try {
                    // SMTP設定
                    if (MAIL_USE_SMTP) {
                        $mail->isSMTP();
                        $mail->Host = MAIL_SMTP_HOST;
                        $mail->SMTPAuth = true;
                        $mail->Username = MAIL_SMTP_USER;
                        $mail->Password = MAIL_SMTP_PASS;
                        $mail->SMTPSecure = MAIL_SMTP_SECURE;
                        $mail->Port = MAIL_SMTP_PORT;
                    }

                    // 送信者設定
                    $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
                    $mail->addAddress($contact['email'], $contact['name']);

                    // メール内容
                    $mail->CharSet = 'UTF-8';
                    $mail->isHTML(false);
                    $mail->Subject = $replySubject;

                    // 返信メッセージ作成
                    $mailBody = $contact['name'] . " 様\n\n";
                    $mailBody .= "この度は、お問い合わせいただきありがとうございます。\n";
                    $mailBody .= "いただきましたお問い合わせについて、以下のとおり回答いたします。\n\n";
                    $mailBody .= "■ お問い合わせ内容\n";
                    $mailBody .= "件名: " . $contact['subject'] . "\n";
                    $mailBody .= "内容: " . $contact['message'] . "\n\n";
                    $mailBody .= "■ 回答内容\n";
                    $mailBody .= $replyMessage . "\n\n";
                    $mailBody .= "ご不明な点がございましたら、お気軽にお問い合わせください。\n\n";
                    $mailBody .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    $mailBody .= COMPANY_NAME . "\n";
                    $mailBody .= "〒" . COMPANY_POSTAL_CODE . " " . COMPANY_ADDRESS . "\n";
                    $mailBody .= "TEL: " . COMPANY_TEL . "\n";
                    $mailBody .= "Email: " . COMPANY_EMAIL . "\n";
                    $mailBody .= "URL: " . site_url() . "\n";
                    $mailBody .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

                    $mail->Body = $mailBody;

                    $mail->send();

                    // 返信済みフラグを更新
                    $db->update('contacts', [
                        'is_replied' => 1,
                        'reply_subject' => $replySubject,
                        'reply_message' => $replyMessage,
                        'reply_sent_at' => date('Y-m-d H:i:s')
                    ], 'id = :id', ['id' => $id]);

                    redirect('admin/contacts/' . $id);
                    return;

                } catch (Exception $e) {
                    error_log('Reply mail send error: ' . $e->getMessage());
                    $errors['general'] = ['メール送信中にエラーが発生しました。'];
                }
            }

        } catch (Exception $e) {
            error_log('Reply error: ' . $e->getMessage());
            $errors['general'] = ['返信処理中にエラーが発生しました。'];
        }

        // エラー時は返信ページを再表示
        $data = [
            'page' => 'admin/contacts/reply',
            'title' => 'お問い合わせ返信',
            'contact' => $contact,
            'errors' => $errors,
            'formData' => [
                'reply_subject' => $replySubject ?? '',
                'reply_message' => $replyMessage ?? ''
            ]
        ];

        return $this->renderAdmin($data);
    }

    public function delete($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        // お問い合わせ存在確認
        $contact = $db->fetch("SELECT * FROM contacts WHERE id = :id", ['id' => $id]);
        if (!$contact) {
            redirect('admin/contacts');
            return;
        }

        try {
            $db->delete('contacts', 'id = :id', ['id' => $id]);
        } catch (Exception $e) {
            error_log('Contact delete error: ' . $e->getMessage());
        }

        redirect('admin/contacts');
    }

    public function markAsRead($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        try {
            $db->update('contacts', ['is_read' => 1], 'id = :id', ['id' => $id]);
        } catch (Exception $e) {
            error_log('Mark as read error: ' . $e->getMessage());
        }

        redirect('admin/contacts');
    }

    public function markAsUnread($id)
    {
        Auth::requireLogin();

        $db = Db::getInstance();

        try {
            $db->update('contacts', ['is_read' => 0], 'id = :id', ['id' => $id]);
        } catch (Exception $e) {
            error_log('Mark as unread error: ' . $e->getMessage());
        }

        redirect('admin/contacts');
    }

    private function renderAdmin($data)
    {
        extract($data);

        ob_start();
        require APP_PATH . '/Views/admin/layouts/main.php';
        return ob_get_clean();
    }
}