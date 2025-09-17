<?php
// バリデーションヘルパー

class Validator
{
    private $data = [];
    private $errors = [];
    private $rules = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        $this->rules = $rules;

        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $fieldRules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;

            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule)
    {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $parameter = null;
        }

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, $this->getFieldName($field) . 'は必須項目です。');
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, $this->getFieldName($field) . 'の形式が正しくありません。');
                }
                break;

            case 'min':
                if (!empty($value) && mb_strlen($value) < (int)$parameter) {
                    $this->addError($field, $this->getFieldName($field) . 'は' . $parameter . '文字以上で入力してください。');
                }
                break;

            case 'max':
                if (!empty($value) && mb_strlen($value) > (int)$parameter) {
                    $this->addError($field, $this->getFieldName($field) . 'は' . $parameter . '文字以内で入力してください。');
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, $this->getFieldName($field) . 'は数値で入力してください。');
                }
                break;

            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, $this->getFieldName($field) . 'は整数で入力してください。');
                }
                break;

            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, $this->getFieldName($field) . 'のURL形式が正しくありません。');
                }
                break;

            case 'unique':
                if (!empty($value)) {
                    list($table, $column) = explode(',', $parameter);
                    $db = Db::getInstance();
                    if ($db->exists($table, "{$column} = :{$column}", [$column => $value])) {
                        $this->addError($field, $this->getFieldName($field) . 'は既に使用されています。');
                    }
                }
                break;

            case 'exists':
                if (!empty($value)) {
                    list($table, $column) = explode(',', $parameter);
                    $db = Db::getInstance();
                    if (!$db->exists($table, "{$column} = :{$column}", [$column => $value])) {
                        $this->addError($field, $this->getFieldName($field) . 'が存在しません。');
                    }
                }
                break;

            case 'file':
                if (isset($_FILES[$field])) {
                    $file = $_FILES[$field];
                    if ($file['error'] !== UPLOAD_ERR_OK && $file['error'] !== UPLOAD_ERR_NO_FILE) {
                        $this->addError($field, $this->getFieldName($field) . 'のアップロードでエラーが発生しました。');
                    }
                }
                break;

            case 'image':
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$field];
                    $imageInfo = getimagesize($file['tmp_name']);
                    if (!$imageInfo) {
                        $this->addError($field, $this->getFieldName($field) . 'は画像ファイルを選択してください。');
                    }
                }
                break;

            case 'mimes':
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$field];
                    $allowedTypes = explode(',', $parameter);
                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                    if (!in_array($fileExtension, $allowedTypes)) {
                        $this->addError($field, $this->getFieldName($field) . 'は' . implode('、', $allowedTypes) . 'ファイルのみアップロード可能です。');
                    }
                }
                break;

            case 'max_size':
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$field];
                    $maxSize = (int)$parameter * 1024 * 1024; // MB to bytes

                    if ($file['size'] > $maxSize) {
                        $this->addError($field, $this->getFieldName($field) . 'のファイルサイズは' . $parameter . 'MB以下にしてください。');
                    }
                }
                break;

            case 'phone':
                if (!empty($value) && !preg_match('/^[\d\-\(\)\+\s]+$/', $value)) {
                    $this->addError($field, $this->getFieldName($field) . 'の形式が正しくありません。');
                }
                break;

            case 'slug':
                if (!empty($value) && !preg_match('/^[a-z0-9\-_]+$/', $value)) {
                    $this->addError($field, $this->getFieldName($field) . 'は英数字、ハイフン、アンダースコアのみ使用可能です。');
                }
                break;
        }
    }

    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    private function getFieldName($field)
    {
        $fieldNames = [
            'name' => 'お名前',
            'email' => 'メールアドレス',
            'phone' => '電話番号',
            'address' => 'ご住所',
            'subject' => '件名',
            'message' => 'お問い合わせ内容',
            'title' => 'タイトル',
            'description' => '概要',
            'content' => '内容',
            'slug' => 'スラッグ',
            'category_id' => 'カテゴリー',
            'location' => '所在地',
            'construction_period' => '工期',
            'floor_area' => '延床面積',
            'structure' => '構造',
            'username' => 'ユーザー名',
            'password' => 'パスワード',
            'password_confirm' => 'パスワード（確認）',
        ];

        return $fieldNames[$field] ?? $field;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getError($field)
    {
        return $this->errors[$field] ?? [];
    }

    public function hasError($field = null)
    {
        if ($field) {
            return isset($this->errors[$field]);
        }
        return !empty($this->errors);
    }

    public function getErrorMessages()
    {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            $messages = array_merge($messages, $fieldErrors);
        }
        return $messages;
    }

    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }

        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}