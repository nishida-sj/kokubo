-- ================================
-- 既存のテーブルがある場合はこちらを使用
-- ================================

-- 1. まず、既存のcategoriesテーブルの構造を確認
-- DESCRIBE categories;

-- 2. display_orderカラムが存在しない場合のみ実行
-- （既に存在する場合はこのSQLをスキップしてください）

-- categoriesテーブルにdisplay_orderカラムを追加
-- ALTER TABLE categories ADD COLUMN display_order INT DEFAULT 0 AFTER name;

-- 3. tagsテーブルを作成
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. work_tagsテーブルを作成
CREATE TABLE IF NOT EXISTS work_tags (
    work_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (work_id, tag_id),
    FOREIGN KEY (work_id) REFERENCES works(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. デフォルトタグを挿入
INSERT IGNORE INTO tags (name, display_order) VALUES
('植樹工事', 1),
('造園工事', 2),
('庭木管理', 3),
('個人邸', 4),
('公共施設', 5),
('商業施設', 6);

-- 6. デフォルトカテゴリを挿入（既存データがあればスキップされます）
INSERT IGNORE INTO categories (name, display_order) VALUES
('植樹工事', 1),
('造園工事', 2),
('庭木管理', 3),
('エクステリア', 4);
