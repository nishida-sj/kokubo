-- 小久保植樹園 ダミーデータ
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- 管理者データ
INSERT INTO `admins` (`username`, `email`, `password`, `name`) VALUES
('admin', 'admin@kokubosyokuju.geo.jp', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '管理者');

-- カテゴリーデータ
INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES
('庭園設計', 'teien-sekkei', 1),
('植栽工事', 'shokusai-kouji', 2),
('樹木管理', 'jumoku-kanri', 3),
('剪定作業', 'sentei-sagyou', 4);

-- タグデータ
INSERT INTO `tags` (`name`, `slug`) VALUES
('和風庭園', 'wafuu-teien'),
('洋風ガーデン', 'youfuu-garden'),
('常緑樹', 'joryokuju'),
('落葉樹', 'rakuyoju'),
('花木', 'kaboku'),
('生垣', 'ikegaki'),
('芝生', 'shibahu'),
('造園工事', 'zouen-kouji');

-- 実績データ
INSERT INTO `works` (`title`, `slug`, `category_id`, `description`, `content`, `location`, `construction_period`, `floor_area`, `structure`, `main_image`, `is_featured`, `is_published`, `sort_order`, `meta_title`, `meta_description`) VALUES
('伊勢市内 和風庭園設計', 'ise-wafuu-teien', 1, '伝統的な日本庭園の美しさを現代住宅に取り入れた和風庭園です。四季を通じて楽しめる植栽配置にこだわりました。', '## 和風庭園設計\n\n伝統的な日本庭園の技法を用いながら、現代の住宅に合う和風庭園を設計・施工いたしました。\n\n### 設計のポイント\n- 季節感を大切にした植栽選択\n- 水の流れを活かした造園\n- 石組みによる自然な景観\n- メンテナンスのしやすさを考慮\n\n### 主要な植栽\n- 常緑樹：マツ、カシ類\n- 落葉樹：モミジ、サクラ\n- 下草：シダ類、苔類\n\n### お客様の声\n「四季折々の美しさを楽しめる庭になりました。小久保さんの丁寧な施工に感謝しています。」', '伊勢市小俣町', '2ヶ月', '150㎡', '和風庭園', '/assets/img/works/work01-main.jpg', 1, 1, 1, '伊勢市内 和風庭園設計 | 小久保植樹園の施工実績', '伝統的な日本庭園を現代住宅に。四季を楽しむ和風庭園の設計・施工実績をご紹介します。'),

('マンション植栽工事', 'mansion-shokusai', 2, '新築マンションのエントランス周辺とお庭の植栽工事を行いました。住民の皆様に愛される緑豊かな環境を目指しました。', '## マンション植栽工事\n\n新築分譲マンションのエントランス周辺とお庭エリアの植栽工事を担当させていただきました。\n\n### 工事のポイント\n- 管理しやすい植栽選択\n- 季節感のある花木の配置\n- プライバシーに配慮した生垣\n- 子供が安全に遊べる芝生エリア\n\n### 植栽内容\n- 常緑高木：シラカシ、クスノキ\n- 花木：サツキ、ツツジ、アジサイ\n- 生垣：レッドロビン\n- 芝生：高麗芝\n\n### 管理計画\n年4回の定期メンテナンスも承っております。', '伊勢市河崎', '1ヶ月', '300㎡', '植栽工事', '/assets/img/works/work02-main.jpg', 1, 1, 2, 'マンション植栽工事 | 小久保植樹園の施工実績', '新築マンションの植栽工事。住民に愛される緑豊かな環境づくりの実績をご紹介。'),

('公園樹木管理', 'kouen-jumoku-kanri', 3, '市立公園の樹木管理を継続的に行っています。安全性と美観を両立した管理を心がけています。', '## 公園樹木管理\n\n伊勢市立○○公園の樹木管理を長年にわたって担当させていただいております。\n\n### 管理内容\n- 定期的な剪定作業\n- 病害虫防除\n- 施肥管理\n- 危険木の診断・処理\n- 補植・植え替え\n\n### 年間スケジュール\n- 春：新芽整理、害虫防除\n- 夏：徒長枝剪定、水やり\n- 秋：本格剪定、施肥\n- 冬：整枝、寒肥\n\n### 安全への取り組み\n樹木医による定期診断を実施し、来園者の安全を第一に考えた管理を行っています。', '伊勢市宮後', '通年', '5,000㎡', '樹木管理', '/assets/img/works/work03-main.jpg', 0, 1, 3, '公園樹木管理 | 小久保植樹園の施工実績', '市立公園の樹木管理。安全性と美観を両立した継続的な管理実績をご紹介。'),

('個人邸剪定作業', 'kojintei-sentei', 4, '個人のお客様宅での庭木剪定作業です。樹種に応じた適切な時期と方法で美しい樹形を維持します。', '## 個人邸剪定作業\n\n個人のお客様のお庭で、様々な樹種の剪定作業を行いました。\n\n### 剪定した樹木\n- マツ（黒松・赤松）：みどり摘み\n- サツキ・ツツジ：花後剪定\n- カキ：冬季剪定\n- ウメ：花芽を考慮した剪定\n\n### 剪定のポイント\n- 樹種ごとの特性を理解した剪定\n- 美しい樹形づくり\n- 病害虫予防\n- お客様の希望に応じた仕上がり\n\n### アフターケア\n剪定後の管理方法もご説明し、年間を通じたお庭のお手入れをサポートいたします。', '伊勢市一身田', '3日', '庭全体', '剪定作業', '/assets/img/works/work04-main.jpg', 0, 1, 4, '個人邸剪定作業 | 小久保植樹園の施工実績', '個人宅での庭木剪定作業。樹種に応じた適切な剪定で美しい庭づくりをサポート。');

-- 実績画像データ
INSERT INTO `work_images` (`work_id`, `path`, `path_thumb`, `alt`, `sort_order`) VALUES
(1, '/assets/img/works/work01-01.jpg', '/assets/img/works/thumbs/work01-01.jpg', '和風モダン住宅 外観', 1),
(1, '/assets/img/works/work01-02.jpg', '/assets/img/works/thumbs/work01-02.jpg', 'リビング・ダイニング', 2),
(1, '/assets/img/works/work01-03.jpg', '/assets/img/works/thumbs/work01-03.jpg', 'ヒノキの階段', 3),
(2, '/assets/img/works/work02-01.jpg', '/assets/img/works/thumbs/work02-01.jpg', '古民家外観（Before）', 1, 1),
(2, '/assets/img/works/work02-02.jpg', '/assets/img/works/thumbs/work02-02.jpg', '古民家外観（After）', 2),
(2, '/assets/img/works/work02-03.jpg', '/assets/img/works/thumbs/work02-03.jpg', '梁を活かしたリビング', 3),
(3, '/assets/img/works/work03-01.jpg', '/assets/img/works/thumbs/work03-01.jpg', 'カフェ外観', 1),
(3, '/assets/img/works/work03-02.jpg', '/assets/img/works/thumbs/work03-02.jpg', 'カフェ店内', 2),
(4, '/assets/img/works/work04-01.jpg', '/assets/img/works/thumbs/work04-01.jpg', '二世帯住宅全景', 1);

-- 実績タグ関連
INSERT INTO `work_tags` (`work_id`, `tag_id`) VALUES
(1, 1), (1, 2), (1, 5), (1, 7),  -- 和風モダン住宅
(2, 1), (2, 2), (2, 3), (2, 8),  -- 古民家再生
(3, 1), (3, 6),                   -- カフェ併用住宅
(4, 1), (4, 3), (4, 4);           -- 二世帯住宅

-- サイト設定データ
INSERT INTO `site_settings` (`key`, `value`, `description`) VALUES
('site_name', '小久保植樹園', 'サイト名'),
('site_description', '伊勢市の植樹園。植栽工事・庭園設計・樹木管理を手がける地域密着の造園業者です。', 'サイト説明文'),
('company_name', '小久保植樹園', '会社名'),
('company_address', '〒516-0000 三重県伊勢市○○町○○番地', '会社住所'),
('company_phone', '0596-00-0000', '電話番号'),
('company_email', 'info@kokubosyokuju.geo.jp', 'メールアドレス'),
('business_hours', '平日 8:00-18:00 / 土曜 8:00-17:00', '営業時間'),
('google_analytics', '', 'Google Analytics ID'),
('facebook_url', '', 'Facebook URL'),
('instagram_url', '', 'Instagram URL'),
('meta_keywords', '伊勢市,植樹園,造園,植栽,庭木,剪定,小久保植樹園', 'メタキーワード');