// 小久保工務店 メインJavaScript

(function() {
    'use strict';

    // DOM要素
    const header = document.getElementById('header');
    const menuBtn = document.getElementById('menuBtn');
    const nav = document.getElementById('nav');
    const stickyCta = document.getElementById('stickyCta');

    // スクロール関連の処理
    let lastScrollTop = 0;
    let scrollTimer = null;

    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // ヘッダーのスタイル変更
        if (scrollTop > 100) {
            header.classList.add('header--scrolled');
        } else {
            header.classList.remove('header--scrolled');
        }

        // スティッキーCTAの表示制御（モバイルのみ）
        if (window.innerWidth < 768 && stickyCta) {
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // 下スクロール時は非表示
                stickyCta.classList.add('is-hidden');
            } else {
                // 上スクロール時は表示
                stickyCta.classList.remove('is-hidden');
            }
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

        // スクロール終了後の処理
        if (scrollTimer) {
            clearTimeout(scrollTimer);
        }
        scrollTimer = setTimeout(() => {
            // スクロール終了時の処理があればここに記述
        }, 150);
    }

    // メニューボタンの処理
    function toggleMobileMenu() {
        if (menuBtn && nav) {
            menuBtn.classList.toggle('is-active');
            nav.classList.toggle('is-open');
            document.body.classList.toggle('menu-open');
        }
    }

    // スムーススクロール
    function smoothScroll(target, duration = 800) {
        const targetElement = document.querySelector(target);
        if (!targetElement) return;

        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition - 80; // ヘッダー分のオフセット
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    }

    // フォームバリデーション
    function validateForm(form) {
        const errors = [];
        const requiredFields = form.querySelectorAll('[required]');
        const emailFields = form.querySelectorAll('input[type="email"]');

        // 必須項目チェック
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                errors.push(`${getFieldLabel(field)}は必須項目です。`);
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });

        // メールアドレス形式チェック
        emailFields.forEach(field => {
            if (field.value && !isValidEmail(field.value)) {
                errors.push(`${getFieldLabel(field)}の形式が正しくありません。`);
                field.classList.add('error');
            }
        });

        return errors;
    }

    function getFieldLabel(field) {
        const label = form.querySelector(`label[for="${field.id}"]`);
        return label ? label.textContent.replace('*', '').trim() : field.name;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // エラーメッセージ表示
    function showErrors(errors, container) {
        if (!container) return;

        container.innerHTML = '';
        if (errors.length === 0) return;

        const errorList = document.createElement('ul');
        errorList.className = 'error-list';

        errors.forEach(error => {
            const errorItem = document.createElement('li');
            errorItem.textContent = error;
            errorList.appendChild(errorItem);
        });

        container.appendChild(errorList);
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // ローディング状態の制御
    function setLoadingState(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = '送信中...';
            button.classList.add('loading');
        } else {
            button.disabled = false;
            button.textContent = button.dataset.originalText;
            button.classList.remove('loading');
        }
    }

    // 画像の遅延読み込み
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // フォールバック：IntersectionObserverがサポートされていない場合
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }

    // ページ表示アニメーション
    function initPageAnimations() {
        const animationElements = document.querySelectorAll('[data-animation]');

        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animationType = element.dataset.animation;
                        element.classList.add('animate', `animate-${animationType}`);
                        animationObserver.unobserve(element);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            animationElements.forEach(el => animationObserver.observe(el));
        }
    }

    // CSRFトークンの取得
    function getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }

    // Ajax リクエストのヘルパー
    function makeRequest(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        };

        return fetch(url, { ...defaultOptions, ...options })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            });
    }

    // ユーティリティ関数
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // イベントリスナーの登録
    function initEventListeners() {
        // スクロールイベント（throttle適用）
        window.addEventListener('scroll', throttle(handleScroll, 16));

        // メニューボタンクリック
        if (menuBtn) {
            menuBtn.addEventListener('click', toggleMobileMenu);
        }

        // スムーススクロールリンク
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (link) {
                e.preventDefault();
                const target = link.getAttribute('href');
                if (target !== '#') {
                    smoothScroll(target);

                    // モバイルメニューが開いている場合は閉じる
                    if (nav && nav.classList.contains('is-open')) {
                        toggleMobileMenu();
                    }
                }
            }
        });

        // フォーム送信処理
        const forms = document.querySelectorAll('form[data-ajax]');
        forms.forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });

        // リサイズイベント（debounce適用）
        window.addEventListener('resize', debounce(() => {
            // リサイズ時の処理
            if (window.innerWidth >= 768 && stickyCta) {
                stickyCta.classList.remove('is-hidden');
            }
        }, 250));

        // ページを離れる前の確認（編集中のフォームがある場合）
        window.addEventListener('beforeunload', (e) => {
            const dirtyForms = document.querySelectorAll('form.dirty');
            if (dirtyForms.length > 0) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });
    }

    // フォーム送信処理
    function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        const errorContainer = form.querySelector('.error-messages');

        // バリデーション
        const errors = validateForm(form);
        if (errors.length > 0) {
            showErrors(errors, errorContainer);
            return;
        }

        // ローディング状態にする
        setLoadingState(submitBtn, true);

        // フォームデータを送信
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 成功時の処理
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // 成功メッセージを表示
                    showSuccessMessage(data.message || '送信が完了しました。');
                    form.reset();
                }
            } else {
                // エラーメッセージを表示
                showErrors(data.errors || ['エラーが発生しました。'], errorContainer);
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showErrors(['通信エラーが発生しました。'], errorContainer);
        })
        .finally(() => {
            setLoadingState(submitBtn, false);
        });
    }

    // 成功メッセージ表示
    function showSuccessMessage(message) {
        // 既存の成功メッセージがあれば削除
        const existingMessage = document.querySelector('.success-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // 成功メッセージを作成・表示
        const messageDiv = document.createElement('div');
        messageDiv.className = 'success-message';
        messageDiv.textContent = message;

        document.body.appendChild(messageDiv);
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // 5秒後に自動で消す
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    // 初期化
    function init() {
        // DOM読み込み完了後の初期化処理
        initEventListeners();
        lazyLoadImages();
        initPageAnimations();

        // 初期スクロール位置でのヘッダースタイル設定
        handleScroll();

        console.log('小久保工務店サイト初期化完了');
    }

    // DOM読み込み完了時に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // グローバルに公開する関数
    window.KokuboSite = {
        smoothScroll,
        validateForm,
        makeRequest,
        showSuccessMessage,
        showErrors
    };

})();