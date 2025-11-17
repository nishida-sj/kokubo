<?php
// サイト設定ヘルパー

class Settings
{
    private static $instance = null;
    private $settings = [];
    private $loaded = false;

    private function __construct()
    {
        // プライベートコンストラクタ
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 設定を読み込む
     */
    private function load()
    {
        if ($this->loaded) {
            return;
        }

        try {
            $db = Db::getInstance();
            $settingsData = $db->fetchAll("SELECT `key`, `value` FROM site_settings");

            foreach ($settingsData as $setting) {
                $this->settings[$setting['key']] = $setting['value'];
            }

            $this->loaded = true;
        } catch (Exception $e) {
            error_log('Settings load error: ' . $e->getMessage());
            $this->loaded = true; // エラーでも再試行しないように
        }
    }

    /**
     * 設定値を取得
     * @param string $key 設定キー
     * @param mixed $default デフォルト値
     * @return mixed
     */
    public function get($key, $default = '')
    {
        $this->load();
        return $this->settings[$key] ?? $default;
    }

    /**
     * 全設定を取得
     * @return array
     */
    public function getAll()
    {
        $this->load();
        return $this->settings;
    }

    /**
     * 設定値が存在するか確認
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $this->load();
        return isset($this->settings[$key]);
    }

    /**
     * 設定をリロード（キャッシュクリア）
     */
    public function reload()
    {
        $this->settings = [];
        $this->loaded = false;
        $this->load();
    }
}

/**
 * 設定値を取得するグローバル関数
 * @param string $key 設定キー
 * @param mixed $default デフォルト値
 * @return mixed
 */
function setting($key, $default = '')
{
    return Settings::getInstance()->get($key, $default);
}
