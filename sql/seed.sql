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
('伊勢市内 和風モダン住宅', 'ise-wafuu-modern', 1, 'ヒノキをふんだんに使用した自然素材の和風モダン住宅です。伝統的な日本建築の美しさと現代的な機能性を融合させました。', '## 物件概要\n\n地元伊勢のヒノキを使用した、和風モダンスタイルの新築住宅です。\n\n### 特徴\n- 自然素材にこだわった内装\n- 開放感のある吹き抜けリビング\n- 伝統工法を活かした構造\n- 省エネ性能の高い断熱材使用\n\n### 施主様の声\n「小久保さんにお願いして本当に良かったです。細部まで丁寧に仕上げていただき、家族全員が大満足しています。」', '伊勢市小俣町', '6ヶ月', '120㎡', '木造軸組工法', '/assets/img/works/work01-main.jpg', 1, 1, 1, '伊勢市内 和風モダン住宅 | 小久保工務店の施工実績', 'ヒノキをふんだんに使用した自然素材の和風モダン住宅。伊勢市の小久保工務店による新築施工実績をご紹介します。'),

('古民家再生リフォーム', 'kominka-saisei', 2, '築100年の古民家を現代の暮らしに合わせて再生したリフォーム事例です。歴史ある梁や柱を活かしながら、快適な住環境を実現。', '## 古民家再生プロジェクト\n\n築100年を超える古民家を、現代の生活スタイルに合わせて全面リノベーションしました。\n\n### リフォームのポイント\n- 既存の梁・柱を活かした設計\n- 断熱性能の大幅向上\n- 水回り設備の完全更新\n- バリアフリー対応\n\n### 工事内容\n- 基礎補強工事\n- 屋根・外壁改修\n- 内装リノベーション\n- 設備更新（電気・ガス・水道）', '伊勢市河崎', '4ヶ月', '150㎡', '木造軸組工法（既存活用）', '/assets/img/works/work02-main.jpg', 1, 1, 2, '古民家再生リフォーム | 小久保工務店の施工実績', '築100年の古民家を現代の暮らしに合わせて再生。歴史を活かした小久保工務店のリフォーム実績をご紹介。'),

('カフェ併用住宅', 'cafe-heiyou-jutaku', 3, '1階にカフェ、2階に住居を配した複合用途の建物です。地域に愛される空間づくりを心がけました。', '## カフェ併用住宅\n\n地域の皆様に愛される空間を目指して設計・施工したカフェ併用住宅です。\n\n### 設計コンセプト\n- 温かみのある木の質感を活かした内装\n- 自然光を取り入れた明るい店内\n- プライバシーに配慮した住居部分\n- バリアフリー設計\n\n### 施設概要\n- 1階：カフェ（客席20席）\n- 2階：住居部分（3LDK）\n- 駐車場：8台完備', '伊勢市宮後', '8ヶ月', '180㎡', '木造軸組工法', '/assets/img/works/work03-main.jpg', 0, 1, 3, 'カフェ併用住宅 | 小久保工務店の施工実績', '1階カフェ、2階住居の複合用途建築。地域に愛される空間づくりを心がけた小久保工務店の施工実績。'),

('二世帯住宅増築', 'nisedai-zoukiku', 4, '既存住宅に隣接して新棟を増築し、二世帯が快適に暮らせる住環境を整備しました。', '## 二世帯住宅増築工事\n\n親世帯・子世帯それぞれのプライバシーを保ちながら、家族のつながりも大切にした二世帯住宅です。\n\n### 計画のポイント\n- 既存棟と新棟を渡り廊下で接続\n- それぞれ独立した玄関\n- 共用できるリビング・ダイニング\n- 各世帯のプライバシーに配慮\n\n### 新築部分仕様\n- 高気密・高断熱仕様\n- オール電化システム\n- 太陽光発電設備\n- 雨水利用システム', '伊勢市一身田', '5ヶ月', '100㎡（増築部分）', '木造軸組工法', '/assets/img/works/work04-main.jpg', 0, 1, 4, '二世帯住宅増築 | 小久保工務店の施工実績', '既存住宅への増築で二世帯住宅を実現。家族のつながりとプライバシーを両立した小久保工務店の施工実績。');

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
('site_name', '小久保工務店', 'サイト名'),
('site_description', '伊勢市の工務店。新築・リフォーム・増改築を手がける地域密着の建築会社です。', 'サイト説明文'),
('company_name', '小久保工務店', '会社名'),
('company_address', '〒516-0000 三重県伊勢市○○町○○番地', '会社住所'),
('company_phone', '0596-00-0000', '電話番号'),
('company_email', 'info@kokubosyokuju.geo.jp', 'メールアドレス'),
('business_hours', '平日 8:00-18:00 / 土曜 8:00-17:00', '営業時間'),
('google_analytics', '', 'Google Analytics ID'),
('facebook_url', '', 'Facebook URL'),
('instagram_url', '', 'Instagram URL'),
('meta_keywords', '伊勢市,工務店,新築,リフォーム,増改築,小久保工務店', 'メタキーワード');