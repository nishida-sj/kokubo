<?php
// 構造化データヘルパー

class Schema
{
    public static function organization()
    {
        $db = Db::getInstance();
        $settings = $db->fetchAll("SELECT `key`, `value` FROM site_settings");

        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['key']] = $setting['value'];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'GeneralContractor',
            'name' => APP_NAME,
            'url' => SITE_URL,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset_url('img/logo.png')
            ],
            'description' => $settingsArray['site_description'] ?? DEFAULT_META_DESCRIPTION,
        ];

        // 住所
        if (!empty($settingsArray['company_address'])) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => '伊勢市',
                'addressRegion' => '三重県',
                'addressCountry' => 'JP',
                'streetAddress' => $settingsArray['company_address']
            ];
        }

        // 電話番号
        if (!empty($settingsArray['company_phone'])) {
            $schema['telephone'] = $settingsArray['company_phone'];
        }

        // メールアドレス
        if (!empty($settingsArray['company_email'])) {
            $schema['email'] = $settingsArray['company_email'];
        }

        // 営業時間
        if (!empty($settingsArray['business_hours'])) {
            $schema['openingHours'] = 'Mo-Sa 08:00-18:00';
        }

        // エリア
        $schema['areaServed'] = [
            '@type' => 'City',
            'name' => '伊勢市'
        ];

        // サービス
        $schema['hasOfferCatalog'] = [
            '@type' => 'OfferCatalog',
            'name' => '建築サービス',
            'itemListElement' => [
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => '新築住宅建築',
                        'description' => '木造住宅の新築工事を承ります。'
                    ]
                ],
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => 'リフォーム・リノベーション',
                        'description' => '住宅のリフォーム・リノベーション工事を承ります。'
                    ]
                ],
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => '増改築',
                        'description' => '住宅の増築・改築工事を承ります。'
                    ]
                ]
            ]
        ];

        return self::jsonLd($schema);
    }

    public static function breadcrumb($items)
    {
        $listItems = [];

        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => isset($item['url']) ? site_url($item['url']) : null
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];

        return self::jsonLd($schema);
    }

    public static function work($work, $images = [])
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $work['title'],
            'description' => $work['description'],
            'url' => site_url('works/' . $work['slug']),
            'author' => [
                '@type' => 'Organization',
                'name' => APP_NAME
            ],
            'dateCreated' => date('c', strtotime($work['created_at'])),
            'dateModified' => date('c', strtotime($work['updated_at']))
        ];

        // メイン画像
        if (!empty($work['main_image'])) {
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => site_url($work['main_image']),
                'name' => $work['title']
            ];
        }

        // 追加画像
        if (!empty($images)) {
            $imageObjects = [];
            foreach ($images as $image) {
                $imageObjects[] = [
                    '@type' => 'ImageObject',
                    'url' => site_url($image['path']),
                    'name' => $image['alt'] ?: $work['title']
                ];
            }

            if (count($imageObjects) === 1) {
                $schema['image'] = $imageObjects[0];
            } else {
                $schema['image'] = $imageObjects;
            }
        }

        // 所在地
        if (!empty($work['location'])) {
            $schema['locationCreated'] = [
                '@type' => 'Place',
                'name' => $work['location']
            ];
        }

        // カテゴリー
        if (!empty($work['category_name'])) {
            $schema['genre'] = $work['category_name'];
        }

        return self::jsonLd($schema);
    }

    public static function worksList($works, $category = null)
    {
        $listItems = [];

        foreach ($works as $index => $work) {
            $item = [
                '@type' => 'CreativeWork',
                'name' => $work['title'],
                'description' => truncate_text($work['description'], 160),
                'url' => site_url('works/' . $work['slug']),
                'dateCreated' => date('c', strtotime($work['created_at']))
            ];

            if (!empty($work['main_image'])) {
                $item['image'] = [
                    '@type' => 'ImageObject',
                    'url' => site_url($work['main_image']),
                    'name' => $work['title']
                ];
            }

            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $item
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $category ? $category['name'] . 'の実績一覧' : '施工実績一覧',
            'description' => $category ?
                $category['name'] . 'の施工実績をご紹介します。' :
                '小久保工務店の施工実績をご紹介します。',
            'itemListElement' => $listItems,
            'numberOfItems' => count($listItems)
        ];

        return self::jsonLd($schema);
    }

    public static function contactPage()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'お問い合わせ',
            'description' => '小久保工務店へのお問い合わせページです。',
            'url' => site_url('contact'),
            'mainEntity' => [
                '@type' => 'Organization',
                'name' => APP_NAME,
                'url' => SITE_URL
            ]
        ];

        return self::jsonLd($schema);
    }

    public static function website()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => APP_NAME,
            'url' => SITE_URL,
            'description' => DEFAULT_META_DESCRIPTION,
            'publisher' => [
                '@type' => 'Organization',
                'name' => APP_NAME,
                'url' => SITE_URL
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => site_url('works?q={search_term_string}')
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];

        return self::jsonLd($schema);
    }

    public static function localBusiness()
    {
        $db = Db::getInstance();
        $settings = $db->fetchAll("SELECT `key`, `value` FROM site_settings");

        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['key']] = $setting['value'];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => APP_NAME,
            'url' => SITE_URL,
            'description' => $settingsArray['site_description'] ?? DEFAULT_META_DESCRIPTION,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => '伊勢市',
                'addressRegion' => '三重県',
                'addressCountry' => 'JP'
            ]
        ];

        if (!empty($settingsArray['company_phone'])) {
            $schema['telephone'] = $settingsArray['company_phone'];
        }

        if (!empty($settingsArray['company_email'])) {
            $schema['email'] = $settingsArray['company_email'];
        }

        return self::jsonLd($schema);
    }

    private static function jsonLd($schema)
    {
        return '<script type="application/ld+json">' .
               json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) .
               '</script>';
    }
}