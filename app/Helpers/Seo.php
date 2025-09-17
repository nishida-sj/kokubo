<?php
// SEO対応ヘルパー

class Seo
{
    private $title = '';
    private $description = '';
    private $keywords = '';
    private $ogTitle = '';
    private $ogDescription = '';
    private $ogImage = '';
    private $ogType = 'website';
    private $canonical = '';
    private $noindex = false;
    private $nofollow = false;

    public function __construct()
    {
        $this->title = DEFAULT_META_TITLE;
        $this->description = DEFAULT_META_DESCRIPTION;
        $this->keywords = DEFAULT_META_KEYWORDS;
        $this->ogImage = OG_IMAGE;
    }

    public function setTitle($title, $siteName = true)
    {
        $this->title = $siteName ? $title . ' | ' . APP_NAME : $title;
        $this->ogTitle = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        $this->ogDescription = $description;
        return $this;
    }

    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $keywords = implode(',', $keywords);
        }
        $this->keywords = $keywords;
        return $this;
    }

    public function setOgTitle($title)
    {
        $this->ogTitle = $title;
        return $this;
    }

    public function setOgDescription($description)
    {
        $this->ogDescription = $description;
        return $this;
    }

    public function setOgImage($image)
    {
        if (strpos($image, 'http') !== 0) {
            $image = site_url($image);
        }
        $this->ogImage = $image;
        return $this;
    }

    public function setOgType($type)
    {
        $this->ogType = $type;
        return $this;
    }

    public function setCanonical($url)
    {
        if (strpos($url, 'http') !== 0) {
            $url = site_url($url);
        }
        $this->canonical = $url;
        return $this;
    }

    public function setNoindex($noindex = true)
    {
        $this->noindex = $noindex;
        return $this;
    }

    public function setNofollow($nofollow = true)
    {
        $this->nofollow = $nofollow;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function renderMeta()
    {
        $html = '';

        // Basic meta tags
        $html .= '<title>' . h($this->title) . '</title>' . "\n";
        $html .= '<meta name="description" content="' . h($this->description) . '">' . "\n";

        if (!empty($this->keywords)) {
            $html .= '<meta name="keywords" content="' . h($this->keywords) . '">' . "\n";
        }

        // Robots meta
        $robots = [];
        if ($this->noindex) $robots[] = 'noindex';
        if ($this->nofollow) $robots[] = 'nofollow';

        if (!empty($robots)) {
            $html .= '<meta name="robots" content="' . implode(',', $robots) . '">' . "\n";
        }

        // Canonical
        if (!empty($this->canonical)) {
            $html .= '<link rel="canonical" href="' . h($this->canonical) . '">' . "\n";
        }

        // Open Graph
        $html .= '<meta property="og:site_name" content="' . h(APP_NAME) . '">' . "\n";
        $html .= '<meta property="og:type" content="' . h($this->ogType) . '">' . "\n";
        $html .= '<meta property="og:title" content="' . h($this->ogTitle ?: $this->title) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . h($this->ogDescription ?: $this->description) . '">' . "\n";

        if (!empty($this->ogImage)) {
            $html .= '<meta property="og:image" content="' . h($this->ogImage) . '">' . "\n";
        }

        if (!empty($this->canonical)) {
            $html .= '<meta property="og:url" content="' . h($this->canonical) . '">' . "\n";
        }

        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . h($this->ogTitle ?: $this->title) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . h($this->ogDescription ?: $this->description) . '">' . "\n";

        if (!empty($this->ogImage)) {
            $html .= '<meta name="twitter:image" content="' . h($this->ogImage) . '">' . "\n";
        }

        return $html;
    }

    public function renderStructuredData()
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => APP_NAME,
            'url' => SITE_URL,
        ];

        // 会社情報を取得
        $db = Db::getInstance();
        $settings = $db->fetchAll("SELECT `key`, `value` FROM site_settings WHERE `key` IN ('company_address', 'company_phone', 'company_email')");

        foreach ($settings as $setting) {
            switch ($setting['key']) {
                case 'company_address':
                    $structuredData['address'] = $setting['value'];
                    break;
                case 'company_phone':
                    $structuredData['telephone'] = $setting['value'];
                    break;
                case 'company_email':
                    $structuredData['email'] = $setting['value'];
                    break;
            }
        }

        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    // 静的メソッド
    public static function createForWork($work)
    {
        $seo = new self();

        $title = $work['meta_title'] ?: $work['title'];
        $description = $work['meta_description'] ?: truncate_text(strip_tags($work['description']), 160);

        $seo->setTitle($title)
            ->setDescription($description)
            ->setOgType('article')
            ->setCanonical('works/' . $work['slug']);

        if (!empty($work['main_image'])) {
            $seo->setOgImage($work['main_image']);
        }

        return $seo;
    }

    public static function createForCategory($category, $page = 1)
    {
        $seo = new self();

        $title = $category['name'] . 'の実績';
        if ($page > 1) {
            $title .= ' - ' . $page . 'ページ目';
        }

        $description = $category['name'] . 'の施工実績をご紹介します。小久保工務店の豊富な経験と技術力をご確認ください。';

        $seo->setTitle($title)
            ->setDescription($description)
            ->setCanonical('works?category=' . $category['slug'] . ($page > 1 ? '&page=' . $page : ''));

        return $seo;
    }

    public static function createForContact()
    {
        $seo = new self();

        return $seo->setTitle('お問い合わせ')
            ->setDescription('小久保工務店へのお問い合わせはこちらから。新築・リフォーム・増改築のご相談承ります。お気軽にお声かけください。')
            ->setCanonical('contact');
    }

    public static function createForHome()
    {
        $seo = new self();

        return $seo->setTitle(DEFAULT_META_TITLE, false)
            ->setDescription(DEFAULT_META_DESCRIPTION)
            ->setCanonical('');
    }
}