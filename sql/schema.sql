-- 小久保工務店データベーススキーマ
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- 実績カテゴリーテーブル
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'カテゴリー名',
  `slug` varchar(100) NOT NULL UNIQUE COMMENT 'URLスラッグ',
  `sort_order` int(11) DEFAULT 0 COMMENT '並び順',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='実績カテゴリー';

-- 実績テーブル
CREATE TABLE `works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT 'タイトル',
  `slug` varchar(200) NOT NULL UNIQUE COMMENT 'URLスラッグ',
  `category_id` int(11) NOT NULL COMMENT 'カテゴリーID',
  `description` text COMMENT '概要',
  `content` longtext COMMENT '詳細内容',
  `location` varchar(200) COMMENT '所在地',
  `construction_period` varchar(100) COMMENT '工期',
  `floor_area` varchar(100) COMMENT '延床面積',
  `structure` varchar(100) COMMENT '構造',
  `main_image` varchar(500) COMMENT 'メイン画像パス',
  `is_featured` tinyint(1) DEFAULT 0 COMMENT 'おすすめフラグ',
  `is_published` tinyint(1) DEFAULT 1 COMMENT '公開フラグ',
  `sort_order` int(11) DEFAULT 0 COMMENT '並び順',
  `meta_title` varchar(200) COMMENT 'SEO タイトル',
  `meta_description` text COMMENT 'SEO ディスクリプション',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `is_featured` (`is_featured`),
  KEY `is_published` (`is_published`),
  KEY `sort_order` (`sort_order`),
  CONSTRAINT `fk_works_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='施工実績';

-- 実績画像テーブル
CREATE TABLE `work_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_id` int(11) NOT NULL COMMENT '実績ID',
  `path` varchar(500) NOT NULL COMMENT '画像パス',
  `path_thumb` varchar(500) COMMENT 'サムネイル画像パス',
  `alt` varchar(200) COMMENT 'alt属性',
  `sort_order` int(11) DEFAULT 0 COMMENT '並び順',
  `is_before` tinyint(1) DEFAULT 0 COMMENT 'Before画像フラグ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `work_id` (`work_id`),
  KEY `sort_order` (`sort_order`),
  CONSTRAINT `fk_work_images_work` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='実績画像';

-- タグテーブル
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'タグ名',
  `slug` varchar(100) NOT NULL UNIQUE COMMENT 'URLスラッグ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='タグ';

-- 実績タグ関連テーブル
CREATE TABLE `work_tags` (
  `work_id` int(11) NOT NULL COMMENT '実績ID',
  `tag_id` int(11) NOT NULL COMMENT 'タグID',
  PRIMARY KEY (`work_id`, `tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `fk_work_tags_work` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_work_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='実績タグ関連';

-- お問い合わせテーブル
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'お名前',
  `email` varchar(200) NOT NULL COMMENT 'メールアドレス',
  `phone` varchar(50) COMMENT '電話番号',
  `address` text COMMENT 'ご住所',
  `subject` varchar(200) COMMENT '件名',
  `message` text NOT NULL COMMENT 'お問い合わせ内容',
  `ip_address` varchar(45) COMMENT 'IPアドレス',
  `user_agent` text COMMENT 'ユーザーエージェント',
  `is_read` tinyint(1) DEFAULT 0 COMMENT '既読フラグ',
  `is_replied` tinyint(1) DEFAULT 0 COMMENT '返信済みフラグ',
  `reply_subject` varchar(200) COMMENT '返信件名',
  `reply_message` text COMMENT '返信内容',
  `reply_sent_at` timestamp NULL COMMENT '返信送信日時',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_read` (`is_read`),
  KEY `is_replied` (`is_replied`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お問い合わせ';

-- 管理者テーブル
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE COMMENT 'ユーザー名',
  `email` varchar(200) NOT NULL UNIQUE COMMENT 'メールアドレス',
  `password` varchar(255) NOT NULL COMMENT 'パスワードハッシュ',
  `name` varchar(100) NOT NULL COMMENT '表示名',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'アクティブフラグ',
  `last_login` timestamp NULL COMMENT '最終ログイン',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理者';

-- サイト設定テーブル
CREATE TABLE `site_settings` (
  `key` varchar(100) NOT NULL COMMENT '設定キー',
  `value` longtext COMMENT '設定値',
  `description` text COMMENT '説明',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='サイト設定';

-- インデックス追加
CREATE INDEX idx_works_category_published ON works(category_id, is_published, sort_order);
CREATE INDEX idx_works_featured_published ON works(is_featured, is_published, sort_order);
CREATE INDEX idx_contacts_created ON contacts(created_at DESC);