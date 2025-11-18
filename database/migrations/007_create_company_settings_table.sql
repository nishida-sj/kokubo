-- 会社案内設定テーブル
CREATE TABLE IF NOT EXISTS company_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- デフォルトデータ
INSERT INTO company_settings (`key`, `value`) VALUES
('greeting_title', '代表挨拶'),
('greeting_content', '私たちは、緑豊かな環境づくりを通じて、地域社会に貢献することを使命としています。\n\n創業以来、お客様の信頼を第一に、高品質なサービスを提供し続けてまいりました。これからも、伊勢の地で皆様のお役に立てるよう、誠心誠意努めてまいります。\n\n代表取締役 小久保 太郎'),
('overview_name', '小久保植樹園'),
('overview_established', '1984年4月'),
('overview_representative', '小久保 太郎'),
('overview_employees', '15名'),
('overview_business', '植樹工事、造園工事、庭木管理、エクステリア工事'),
('overview_area', '三重県伊勢市および周辺地域'),
('history_1984', '1984年|創業。伊勢市にて植樹・造園業をスタート'),
('history_1995', '1995年|法人化。小久保植樹園株式会社として新たな一歩を踏み出す'),
('history_2005', '2005年|事業拡大。庭木管理サービスを本格的に開始'),
('history_2015', '2015年|ISO認証取得。品質管理体制を強化'),
('history_2024', '2024年|創業40周年。地域に根ざした信頼の実績を築く')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
