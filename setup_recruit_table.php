<?php
// 採用情報テーブルセットアップスクリプト
// このファイルをブラウザで一度だけ実行してください

require_once __DIR__ . '/config/config.php';

try {
    $db = Db::getInstance();

    echo "<h1>採用情報テーブルセットアップ</h1>";

    // テーブル作成
    $db->query("
        CREATE TABLE IF NOT EXISTS recruit_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) NOT NULL UNIQUE,
            `value` TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    echo "<p>✓ recruit_settingsテーブルを作成しました</p>";

    // デフォルトデータを挿入
    $defaultData = [
        ['key' => 'page_title', 'value' => '🌱 採用情報'],
        ['key' => 'page_subtitle', 'value' => '緑豊かな環境づくりを一緒に担う仲間を募集しています'],

        // 職種1
        ['key' => 'job1_enabled', 'value' => '1'],
        ['key' => 'job1_icon', 'value' => '🌳'],
        ['key' => 'job1_title', 'value' => '植栽・造園スタッフ'],
        ['key' => 'job1_description', 'value' => '植栽工事や庭園の設計・施工を担当していただきます。未経験の方も先輩スタッフが丁寧に指導いたします。'],
        ['key' => 'job1_employment_type', 'value' => '正社員'],
        ['key' => 'job1_salary', 'value' => '月給 22万円〜35万円（経験・能力による）'],
        ['key' => 'job1_work_hours', 'value' => '8:00〜17:00（休憩1時間）'],
        ['key' => 'job1_holidays', 'value' => '日曜・祝日・年末年始'],
        ['key' => 'job1_qualifications', 'value' => '普通自動車免許（必須）'],
        ['key' => 'job1_experience', 'value' => '未経験者歓迎'],

        // 職種2
        ['key' => 'job2_enabled', 'value' => '1'],
        ['key' => 'job2_icon', 'value' => '✂️'],
        ['key' => 'job2_title', 'value' => '庭木管理スタッフ'],
        ['key' => 'job2_description', 'value' => '個人宅や企業施設の庭木剪定・管理業務を担当していただきます。技術をしっかりと身につけることができます。'],
        ['key' => 'job2_employment_type', 'value' => '正社員・パート'],
        ['key' => 'job2_salary', 'value' => '時給 1,200円〜1,800円'],
        ['key' => 'job2_work_hours', 'value' => '8:00〜17:00（時間相談可）'],
        ['key' => 'job2_holidays', 'value' => '日曜・祝日（シフト制）'],
        ['key' => 'job2_qualifications', 'value' => '普通自動車免許（必須）'],
        ['key' => 'job2_experience', 'value' => '経験者優遇・未経験者歓迎'],

        // 福利厚生
        ['key' => 'benefits', 'value' => "社会保険完備|健康保険・厚生年金・雇用保険・労災保険に加入\n研修制度|先輩スタッフによる丁寧な技術指導と外部研修参加支援\n交通費支給|通勤手当・現場への交通費を全額支給\n資格取得支援|造園技能士等の資格取得費用を会社が負担\n家族手当|扶養家族がいる場合の手当支給\n各種手当|皆勤手当・賞与年2回・昇給年1回"],

        // 求める人物像
        ['key' => 'requirements', 'value' => "自然や植物が好きな方|緑に囲まれた環境で働きたい方を歓迎します\n体力に自信がある方|屋外での作業が中心となります\n責任感のある方|お客様の大切な庭木を扱う責任ある仕事です\nチームワークを大切にする方|スタッフ同士で協力して作業を進めます\n向上心のある方|技術習得に積極的に取り組める方\n地域に貢献したい方|伊勢の美しい環境づくりに参加しませんか"],

        // CTA
        ['key' => 'cta_title', 'value' => '🌿 一緒に働きませんか？'],
        ['key' => 'cta_description', 'value' => "緑豊かな環境づくりを通じて、地域社会に貢献する仕事です。\n未経験の方も歓迎します。まずはお気軽にお問い合わせください。"],
        ['key' => 'cta_button_text', 'value' => '採用に関するお問い合わせ'],
        ['key' => 'cta_button_url', 'value' => '/contact'],
    ];

    foreach ($defaultData as $data) {
        $db->query(
            "INSERT INTO recruit_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?",
            [$data['key'], $data['value'], $data['value']]
        );
    }

    echo "<p>✓ デフォルトデータを挿入しました</p>";
    echo "<h2 style='color: green;'>セットアップ完了！</h2>";
    echo "<p><a href='/admin/recruit'>採用情報管理画面へ</a></p>";
    echo "<p><a href='/recruit'>採用情報ページを確認</a></p>";
    echo "<p><strong>このファイルは削除してください：setup_recruit_table.php</strong></p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>エラーが発生しました</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
