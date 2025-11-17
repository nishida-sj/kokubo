-- 採用情報設定テーブル
CREATE TABLE IF NOT EXISTS recruit_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- デフォルト値を挿入
INSERT INTO recruit_settings (`key`, `value`) VALUES
('page_title', '🌱 採用情報'),
('page_subtitle', '緑豊かな環境づくりを一緒に担う仲間を募集しています'),

-- 職種1: 植栽・造園スタッフ
('job1_enabled', '1'),
('job1_icon', '🌳'),
('job1_title', '植栽・造園スタッフ'),
('job1_description', '植栽工事や庭園の設計・施工を担当していただきます。未経験の方も先輩スタッフが丁寧に指導いたします。'),
('job1_employment_type', '正社員'),
('job1_salary', '月給 22万円〜35万円（経験・能力による）'),
('job1_work_hours', '8:00〜17:00（休憩1時間）'),
('job1_holidays', '日曜・祝日・年末年始'),
('job1_qualifications', '普通自動車免許（必須）'),
('job1_experience', '未経験者歓迎'),

-- 職種2: 庭木管理スタッフ
('job2_enabled', '1'),
('job2_icon', '✂️'),
('job2_title', '庭木管理スタッフ'),
('job2_description', '個人宅や企業施設の庭木剪定・管理業務を担当していただきます。技術をしっかりと身につけることができます。'),
('job2_employment_type', '正社員・パート'),
('job2_salary', '時給 1,200円〜1,800円'),
('job2_work_hours', '8:00〜17:00（時間相談可）'),
('job2_holidays', '日曜・祝日（シフト制）'),
('job2_qualifications', '普通自動車免許（必須）'),
('job2_experience', '経験者優遇・未経験者歓迎'),

-- 福利厚生
('benefits', '社会保険完備|健康保険・厚生年金・雇用保険・労災保険に加入
研修制度|先輩スタッフによる丁寧な技術指導と外部研修参加支援
交通費支給|通勤手当・現場への交通費を全額支給
資格取得支援|造園技能士等の資格取得費用を会社が負担
家族手当|扶養家族がいる場合の手当支給
各種手当|皆勤手当・賞与年2回・昇給年1回'),

-- 求める人物像
('requirements', '自然や植物が好きな方|緑に囲まれた環境で働きたい方を歓迎します
体力に自信がある方|屋外での作業が中心となります
責任感のある方|お客様の大切な庭木を扱う責任ある仕事です
チームワークを大切にする方|スタッフ同士で協力して作業を進めます
向上心のある方|技術習得に積極的に取り組める方
地域に貢献したい方|伊勢の美しい環境づくりに参加しませんか'),

-- CTA
('cta_title', '🌿 一緒に働きませんか？'),
('cta_description', '緑豊かな環境づくりを通じて、地域社会に貢献する仕事です。
未経験の方も歓迎します。まずはお気軽にお問い合わせください。'),
('cta_button_text', '採用に関するお問い合わせ'),
('cta_button_url', '/contact')

ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
