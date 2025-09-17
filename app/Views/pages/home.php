<?php
// ホーム（ランディングページ）
?>

<!-- ヒーローセクション -->
<section class="hero">
    <div class="hero__background">
        <div class="hero__image">
            <img src="<?= asset_url('img/hero-bg.jpg') ?>" alt="小久保工務店の施工事例" class="hero__image-img">
        </div>
        <div class="hero__overlay"></div>
    </div>

    <div class="hero__container">
        <div class="hero__content">
            <h1 class="hero__title">
                <span class="hero__title-main">伊勢の暮らしに寄り添う</span>
                <span class="hero__title-sub">地域密着の工務店</span>
            </h1>

            <p class="hero__description">
                創業から培ってきた確かな技術と地元への愛着で、<br>
                お客様の理想の住まいづくりをサポートします。<br>
                新築・リフォーム・増改築まで、安心してお任せください。
            </p>

            <div class="hero__cta">
                <a href="<?= site_url('contact') ?>" class="btn btn--primary btn--large">
                    無料お見積り依頼
                </a>
                <a href="#works" class="btn btn--outline btn--large">
                    施工実績を見る
                </a>
            </div>

            <div class="hero__features">
                <div class="hero__feature">
                    <span class="hero__feature-icon">🏠</span>
                    <span class="hero__feature-text">地域密着</span>
                </div>
                <div class="hero__feature">
                    <span class="hero__feature-icon">🔨</span>
                    <span class="hero__feature-text">確かな技術</span>
                </div>
                <div class="hero__feature">
                    <span class="hero__feature-icon">💚</span>
                    <span class="hero__feature-text">自然素材</span>
                </div>
            </div>
        </div>

        <div class="hero__contact">
            <div class="hero__contact-item">
                <span class="hero__contact-label">お電話でのお問い合わせ</span>
                <a href="tel:0596-00-0000" class="hero__contact-tel">0596-00-0000</a>
                <span class="hero__contact-hours">営業時間: 平日 8:00-18:00 / 土曜 8:00-17:00</span>
            </div>
        </div>
    </div>

    <div class="hero__scroll">
        <a href="#about" class="hero__scroll-link">
            <span class="hero__scroll-text">SCROLL</span>
            <span class="hero__scroll-arrow">↓</span>
        </a>
    </div>
</section>

<!-- 特徴セクション -->
<section class="about" id="about">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" data-animation="fadeInUp">小久保工務店の特徴</h2>
            <p class="section-description" data-animation="fadeInUp">
                伊勢の地で長年培ってきた経験と技術で、お客様の想いを形にします。
            </p>
        </div>

        <div class="about__grid">
            <div class="about__item" data-animation="fadeInUp">
                <div class="about__item-icon">
                    <img src="<?= asset_url('img/icon-local.svg') ?>" alt="地域密着">
                </div>
                <h3 class="about__item-title">地域密着40年</h3>
                <p class="about__item-text">
                    伊勢の気候風土を知り尽くした地域密着の工務店です。地元の皆様に愛され続けて40年、これまでに500棟を超える実績があります。
                </p>
            </div>

            <div class="about__item" data-animation="fadeInUp">
                <div class="about__item-icon">
                    <img src="<?= asset_url('img/icon-skill.svg') ?>" alt="確かな技術">
                </div>
                <h3 class="about__item-title">確かな技術力</h3>
                <p class="about__item-text">
                    伝統的な木造建築の技術を受け継ぎながら、最新の建築技術も積極的に取り入れ、長く安心して住める家づくりを行っています。
                </p>
            </div>

            <div class="about__item" data-animation="fadeInUp">
                <div class="about__item-icon">
                    <img src="<?= asset_url('img/icon-nature.svg') ?>" alt="自然素材">
                </div>
                <h3 class="about__item-title">自然素材へのこだわり</h3>
                <p class="about__item-text">
                    地元三重県産のヒノキを中心とした自然素材を使用。シックハウス対策にも配慮し、家族みんなが健康で快適に過ごせる住環境を提供します。
                </p>
            </div>

            <div class="about__item" data-animation="fadeInUp">
                <div class="about__item-icon">
                    <img src="<?= asset_url('img/icon-support.svg') ?>" alt="充実サポート">
                </div>
                <h3 class="about__item-title">充実のアフターサポート</h3>
                <p class="about__item-text">
                    お引き渡し後も安心の定期点検とメンテナンスサポート。地域密着だからこそできる、迅速で丁寧な対応をお約束します。
                </p>
            </div>
        </div>
    </div>
</section>

<!-- サービスセクション -->
<section class="services" id="services">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" data-animation="fadeInUp">提供サービス</h2>
            <p class="section-description" data-animation="fadeInUp">
                新築からリフォームまで、住まいに関するあらゆるご要望にお応えします。
            </p>
        </div>

        <div class="services__grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $index => $category): ?>
                    <div class="services__item" data-animation="fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                        <div class="services__item-image">
                            <img src="<?= asset_url('img/service-' . ($index + 1) . '.jpg') ?>" alt="<?= h($category['name']) ?>" class="services__item-img">
                            <div class="services__item-overlay">
                                <span class="services__item-count"><?= $category['works_count'] ?>件の実績</span>
                            </div>
                        </div>
                        <div class="services__item-content">
                            <h3 class="services__item-title"><?= h($category['name']) ?></h3>
                            <p class="services__item-description">
                                <?php
                                $descriptions = [
                                    '新築住宅' => 'お客様のライフスタイルに合わせた理想の住まいを一から設計・施工いたします。',
                                    'リフォーム' => '住まいの悩みを解決し、より快適で機能的な空間に生まれ変わらせます。',
                                    '店舗・事務所' => 'ビジネスの成功をサポートする、機能的で魅力的な商業施設を提供します。',
                                    '増改築' => '家族構成の変化に合わせて、既存の住まいを効率的に拡張・改修します。'
                                ];
                                echo h($descriptions[$category['name']] ?? '');
                                ?>
                            </p>
                            <a href="<?= site_url('works?category=' . $category['slug']) ?>" class="services__item-link">
                                実績を見る <span>→</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 施工実績セクション -->
<section class="works" id="works">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" data-animation="fadeInUp">施工実績</h2>
            <p class="section-description" data-animation="fadeInUp">
                これまでに手がけた代表的な施工事例をご紹介します。
            </p>
        </div>

        <?php if (!empty($featuredWorks)): ?>
            <div class="works__grid">
                <?php foreach ($featuredWorks as $index => $work): ?>
                    <article class="works__item" data-animation="fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                        <a href="<?= site_url('works/' . $work['slug']) ?>" class="works__item-link">
                            <div class="works__item-image">
                                <img src="<?= $work['main_image'] ? site_url($work['main_image']) : asset_url('img/no-image.jpg') ?>"
                                     alt="<?= h($work['title']) ?>" class="works__item-img">
                                <div class="works__item-category">
                                    <?= h($work['category_name']) ?>
                                </div>
                            </div>
                            <div class="works__item-content">
                                <h3 class="works__item-title"><?= h($work['title']) ?></h3>
                                <p class="works__item-description">
                                    <?= h(truncate_text($work['description'], 80)) ?>
                                </p>
                                <div class="works__item-meta">
                                    <?php if (!empty($work['location'])): ?>
                                        <span class="works__item-location">📍 <?= h($work['location']) ?></span>
                                    <?php endif; ?>
                                    <span class="works__item-date"><?= format_date($work['created_at'], 'Y年n月') ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="works__more" data-animation="fadeInUp">
            <a href="<?= site_url('works') ?>" class="btn btn--outline btn--large">
                すべての施工実績を見る
            </a>
        </div>
    </div>
</section>

<!-- お客様の声セクション -->
<section class="testimonials">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title" data-animation="fadeInUp">お客様の声</h2>
            <p class="section-description" data-animation="fadeInUp">
                実際に小久保工務店で家を建てられたお客様からの声をご紹介します。
            </p>
        </div>

        <div class="testimonials__grid">
            <div class="testimonials__item" data-animation="fadeInUp">
                <div class="testimonials__content">
                    <p class="testimonials__text">
                        「小久保さんにお願いして本当に良かったです。細部まで丁寧に仕上げていただき、家族全員が大満足しています。自然素材の温もりを感じられる、理想的な住まいになりました。」
                    </p>
                    <div class="testimonials__author">
                        <span class="testimonials__name">S様ご家族</span>
                        <span class="testimonials__location">伊勢市小俣町</span>
                    </div>
                </div>
                <div class="testimonials__image">
                    <img src="<?= asset_url('img/testimonial-1.jpg') ?>" alt="お客様の声" class="testimonials__img">
                </div>
            </div>

            <div class="testimonials__item" data-animation="fadeInUp">
                <div class="testimonials__content">
                    <p class="testimonials__text">
                        「築100年の古民家のリフォームをお願いしました。歴史ある梁や柱を活かしながら、現代の暮らしに合わせて快適に仕上げていただき、感謝しています。」
                    </p>
                    <div class="testimonials__author">
                        <span class="testimonials__name">T様ご家族</span>
                        <span class="testimonials__location">伊勢市河崎</span>
                    </div>
                </div>
                <div class="testimonials__image">
                    <img src="<?= asset_url('img/testimonial-2.jpg') ?>" alt="お客様の声" class="testimonials__img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- お問い合わせセクション -->
<section class="contact-cta">
    <div class="container">
        <div class="contact-cta__content" data-animation="fadeInUp">
            <h2 class="contact-cta__title">お気軽にご相談ください</h2>
            <p class="contact-cta__description">
                新築・リフォーム・増改築に関するご相談やお見積りは無料です。<br>
                まずはお気軽にお問い合わせください。
            </p>

            <div class="contact-cta__methods">
                <div class="contact-cta__method">
                    <div class="contact-cta__method-icon">📞</div>
                    <div class="contact-cta__method-content">
                        <h3 class="contact-cta__method-title">お電話でのお問い合わせ</h3>
                        <a href="tel:0596-00-0000" class="contact-cta__tel">0596-00-0000</a>
                        <p class="contact-cta__hours">営業時間: 平日 8:00-18:00 / 土曜 8:00-17:00</p>
                    </div>
                </div>

                <div class="contact-cta__method">
                    <div class="contact-cta__method-icon">✉</div>
                    <div class="contact-cta__method-content">
                        <h3 class="contact-cta__method-title">メールでのお問い合わせ</h3>
                        <a href="<?= site_url('contact') ?>" class="btn btn--primary btn--large">
                            お見積り依頼フォーム
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ホーム（LP）専用スタイル */

/* ヒーローセクション */
.hero {
    position: relative;
    height: 100vh;
    min-height: 600px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero__background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.hero__image {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.hero__image-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.hero__overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(139, 69, 19, 0.8) 0%, rgba(47, 82, 51, 0.6) 100%);
}

.hero__container {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: var(--container-xl);
    margin: 0 auto;
    padding: 0 var(--space-4);
    color: var(--c-white);
}

.hero__content {
    max-width: 600px;
}

.hero__title {
    margin-bottom: var(--space-6);
}

.hero__title-main {
    display: block;
    font-size: var(--fs-3xl);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--space-2);
}

.hero__title-sub {
    display: block;
    font-size: var(--fs-5xl);
    font-weight: 700;
    line-height: 1.1;
    color: var(--c-accent-light);
}

@media (min-width: 768px) {
    .hero__title-main {
        font-size: var(--fs-4xl);
    }

    .hero__title-sub {
        font-size: calc(var(--fs-5xl) + 1rem);
    }
}

.hero__description {
    font-size: var(--fs-lg);
    line-height: 1.8;
    margin-bottom: var(--space-8);
    color: rgba(255, 255, 255, 0.9);
}

.hero__cta {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-12);
}

@media (min-width: 640px) {
    .hero__cta {
        flex-direction: row;
        gap: var(--space-6);
    }
}

.hero__features {
    display: flex;
    gap: var(--space-8);
    margin-bottom: var(--space-16);
}

.hero__feature {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--fs-base);
    font-weight: 500;
}

.hero__feature-icon {
    font-size: var(--fs-xl);
}

.hero__contact {
    margin-top: var(--space-16);
}

.hero__contact-item {
    text-align: center;
    padding: var(--space-4);
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
}

@media (min-width: 768px) {
    .hero__contact-item {
        display: inline-block;
        text-align: left;
    }
}

.hero__contact-label {
    display: block;
    font-size: var(--fs-sm);
    margin-bottom: var(--space-1);
    opacity: 0.8;
}

.hero__contact-tel {
    display: block;
    font-size: var(--fs-2xl);
    font-weight: 700;
    font-family: var(--font-en);
    color: var(--c-white);
    text-decoration: none;
    margin-bottom: var(--space-1);
}

.hero__contact-tel:hover {
    color: var(--c-accent-light);
}

.hero__contact-hours {
    font-size: var(--fs-xs);
    opacity: 0.7;
}

.hero__scroll {
    position: absolute;
    bottom: var(--space-8);
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.hero__scroll-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-2);
    color: var(--c-white);
    text-decoration: none;
    font-size: var(--fs-sm);
    font-weight: 500;
    opacity: 0.8;
    transition: all var(--transition-base);
}

.hero__scroll-link:hover {
    opacity: 1;
    transform: translateY(-5px);
}

.hero__scroll-arrow {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* セクション共通 */
.section-header {
    text-align: center;
    margin-bottom: var(--space-16);
}

.section-title {
    font-size: var(--fs-3xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background-color: var(--c-primary);
    margin: var(--space-4) auto 0;
}

.section-description {
    font-size: var(--fs-lg);
    color: var(--c-text-light);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}

/* 特徴セクション */
.about {
    padding: var(--space-24) 0;
    background-color: var(--c-white);
}

.about__grid {
    display: grid;
    gap: var(--space-12);
}

@media (min-width: 768px) {
    .about__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-16);
    }
}

@media (min-width: 1024px) {
    .about__grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-8);
    }
}

.about__item {
    text-align: center;
    padding: var(--space-8);
    background-color: var(--c-bg);
    border-radius: var(--radius-lg);
    transition: transform var(--transition-base);
}

.about__item:hover {
    transform: translateY(-5px);
}

.about__item-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto var(--space-6);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--c-primary);
    border-radius: 50%;
}

.about__item-icon img {
    width: 40px;
    height: 40px;
    filter: brightness(0) invert(1);
}

.about__item-title {
    font-size: var(--fs-xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-4);
}

.about__item-text {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.7;
}

/* サービスセクション */
.services {
    padding: var(--space-24) 0;
    background-color: var(--c-gray-100);
}

.services__grid {
    display: grid;
    gap: var(--space-8);
}

@media (min-width: 768px) {
    .services__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-12);
    }
}

@media (min-width: 1024px) {
    .services__grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.services__item {
    background-color: var(--c-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
    transition: all var(--transition-base);
}

.services__item:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}

.services__item-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}

.services__item-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.services__item:hover .services__item-img {
    transform: scale(1.05);
}

.services__item-overlay {
    position: absolute;
    top: var(--space-4);
    right: var(--space-4);
    background-color: var(--c-primary);
    color: var(--c-white);
    font-size: var(--fs-xs);
    font-weight: 500;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
}

.services__item-content {
    padding: var(--space-6);
}

.services__item-title {
    font-size: var(--fs-xl);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-3);
}

.services__item-description {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.7;
    margin-bottom: var(--space-4);
}

.services__item-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--c-primary);
    font-weight: 500;
    text-decoration: none;
    transition: color var(--transition-base);
}

.services__item-link:hover {
    color: var(--c-primary-dark);
}

.services__item-link span {
    transition: transform var(--transition-base);
}

.services__item-link:hover span {
    transform: translateX(5px);
}

/* 施工実績セクション */
.works {
    padding: var(--space-24) 0;
    background-color: var(--c-white);
}

.works__grid {
    display: grid;
    gap: var(--space-8);
    margin-bottom: var(--space-16);
}

@media (min-width: 768px) {
    .works__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-12);
    }
}

@media (min-width: 1024px) {
    .works__grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.works__item {
    background-color: var(--c-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-base);
    transition: all var(--transition-base);
}

.works__item:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}

.works__item-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.works__item-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}

.works__item-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.works__item:hover .works__item-img {
    transform: scale(1.05);
}

.works__item-category {
    position: absolute;
    top: var(--space-4);
    left: var(--space-4);
    background-color: var(--c-secondary);
    color: var(--c-white);
    font-size: var(--fs-xs);
    font-weight: 500;
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-base);
}

.works__item-content {
    padding: var(--space-6);
}

.works__item-title {
    font-size: var(--fs-lg);
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: var(--space-3);
    line-height: 1.4;
}

.works__item-description {
    font-size: var(--fs-base);
    color: var(--c-text-light);
    line-height: 1.6;
    margin-bottom: var(--space-4);
}

.works__item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
}

.works__more {
    text-align: center;
}

/* お客様の声セクション */
.testimonials {
    padding: var(--space-24) 0;
    background-color: var(--c-gray-100);
}

.testimonials__grid {
    display: grid;
    gap: var(--space-12);
}

@media (min-width: 1024px) {
    .testimonials__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-16);
    }
}

.testimonials__item {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
    background-color: var(--c-white);
    padding: var(--space-8);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-base);
}

@media (min-width: 768px) {
    .testimonials__item {
        flex-direction: row;
        align-items: center;
    }
}

.testimonials__content {
    flex: 1;
}

.testimonials__text {
    font-size: var(--fs-base);
    line-height: 1.8;
    color: var(--c-text);
    margin-bottom: var(--space-4);
    position: relative;
    padding-left: var(--space-6);
}

.testimonials__text::before {
    content: '"';
    position: absolute;
    left: 0;
    top: -var(--space-2);
    font-size: var(--fs-3xl);
    color: var(--c-primary);
    font-weight: 700;
}

.testimonials__author {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.testimonials__name {
    font-weight: 700;
    color: var(--c-text);
}

.testimonials__location {
    font-size: var(--fs-sm);
    color: var(--c-text-muted);
}

.testimonials__image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.testimonials__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* お問い合わせCTAセクション */
.contact-cta {
    padding: var(--space-24) 0;
    background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-secondary) 100%);
    color: var(--c-white);
}

.contact-cta__content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.contact-cta__title {
    font-size: var(--fs-3xl);
    font-weight: 700;
    margin-bottom: var(--space-4);
}

.contact-cta__description {
    font-size: var(--fs-lg);
    line-height: 1.7;
    margin-bottom: var(--space-12);
    opacity: 0.9;
}

.contact-cta__methods {
    display: grid;
    gap: var(--space-8);
}

@media (min-width: 768px) {
    .contact-cta__methods {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-12);
    }
}

.contact-cta__method {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-8);
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
}

@media (min-width: 768px) {
    .contact-cta__method {
        flex-direction: row;
        text-align: left;
        align-items: flex-start;
    }
}

.contact-cta__method-icon {
    font-size: var(--fs-4xl);
    flex-shrink: 0;
}

.contact-cta__method-content {
    flex: 1;
}

.contact-cta__method-title {
    font-size: var(--fs-xl);
    font-weight: 700;
    margin-bottom: var(--space-3);
}

.contact-cta__tel {
    display: block;
    font-size: var(--fs-2xl);
    font-weight: 700;
    font-family: var(--font-en);
    color: var(--c-white);
    text-decoration: none;
    margin-bottom: var(--space-2);
    transition: color var(--transition-base);
}

.contact-cta__tel:hover {
    color: var(--c-accent-light);
}

.contact-cta__hours {
    font-size: var(--fs-sm);
    opacity: 0.8;
}

/* アニメーション */
[data-animation] {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
}

[data-animation].animate {
    opacity: 1;
    transform: translateY(0);
}

/* レスポンシブ調整 */
@media (max-width: 767px) {
    .hero {
        height: 90vh;
        min-height: 500px;
    }

    .hero__title-main {
        font-size: var(--fs-2xl);
    }

    .hero__title-sub {
        font-size: var(--fs-3xl);
    }

    .hero__description {
        font-size: var(--fs-base);
    }

    .hero__features {
        flex-direction: column;
        gap: var(--space-4);
    }

    .section-title {
        font-size: var(--fs-2xl);
    }

    .section-description {
        font-size: var(--fs-base);
    }
}
</style>