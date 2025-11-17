-- contactsテーブルに不足しているカラムを追加
-- 実行日: 2024-11-17

-- 既存のcontactsテーブルにカラムを追加
ALTER TABLE `contacts`
ADD COLUMN `is_replied` tinyint(1) DEFAULT 0 COMMENT '返信済みフラグ' AFTER `is_read`,
ADD COLUMN `reply_subject` varchar(200) COMMENT '返信件名' AFTER `is_replied`,
ADD COLUMN `reply_message` text COMMENT '返信内容' AFTER `reply_subject`,
ADD COLUMN `reply_sent_at` timestamp NULL COMMENT '返信送信日時' AFTER `reply_message`,
ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- インデックス追加
ALTER TABLE `contacts`
ADD KEY `is_replied` (`is_replied`);

-- 確認
SHOW COLUMNS FROM `contacts`;
