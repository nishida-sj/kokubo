// 管理画面JavaScript

(function() {
    'use strict';

    // DOM要素
    const sidebar = document.querySelector('.admin-sidebar');
    const menuToggle = document.querySelector('.menu-toggle');

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

    // サイドバーの表示切り替え（モバイル）
    function toggleSidebar() {
        if (sidebar) {
            sidebar.classList.toggle('is-open');
        }
    }

    // フォームの送信処理
    function handleFormSubmit(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // ローディング状態にする
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> 処理中...';
        form.classList.add('loading');

        // 元の状態に戻す関数
        function resetForm() {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            form.classList.remove('loading');
        }

        return resetForm;
    }

    // 削除確認ダイアログ
    function confirmDelete(message = '本当に削除しますか？') {
        return confirm(message);
    }

    // テーブルのソート機能
    function initTableSort() {
        const tables = document.querySelectorAll('.table--sortable');

        tables.forEach(table => {
            const headers = table.querySelectorAll('th[data-sort]');

            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    const currentOrder = header.dataset.order || 'asc';
                    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                    // ソートのクエリパラメータを更新
                    const url = new URL(window.location);
                    url.searchParams.set('sort', column);
                    url.searchParams.set('order', newOrder);
                    url.searchParams.delete('page'); // ページをリセット

                    window.location.href = url.toString();
                });
            });
        });
    }

    // 一括選択チェックボックス
    function initBulkSelect() {
        const selectAll = document.querySelector('#select-all');
        const selectItems = document.querySelectorAll('.select-item');
        const bulkActions = document.querySelector('.bulk-actions');

        if (selectAll && selectItems.length > 0) {
            selectAll.addEventListener('change', () => {
                selectItems.forEach(item => {
                    item.checked = selectAll.checked;
                });
                toggleBulkActions();
            });

            selectItems.forEach(item => {
                item.addEventListener('change', () => {
                    const checkedCount = document.querySelectorAll('.select-item:checked').length;
                    selectAll.checked = checkedCount === selectItems.length;
                    selectAll.indeterminate = checkedCount > 0 && checkedCount < selectItems.length;
                    toggleBulkActions();
                });
            });
        }

        function toggleBulkActions() {
            const checkedCount = document.querySelectorAll('.select-item:checked').length;
            if (bulkActions) {
                bulkActions.style.display = checkedCount > 0 ? 'block' : 'none';
            }
        }
    }

    // リアルタイム検索
    function initLiveSearch() {
        const searchInput = document.querySelector('#live-search');
        const searchResults = document.querySelector('#search-results');

        if (searchInput && searchResults) {
            let searchTimeout;

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                const query = searchInput.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            function performSearch(query) {
                const url = searchInput.dataset.searchUrl || '/admin/search';

                makeRequest(`${url}?q=${encodeURIComponent(query)}`)
                    .then(data => {
                        displaySearchResults(data.results || []);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="search-error">検索エラーが発生しました</div>';
                    });
            }

            function displaySearchResults(results) {
                if (results.length === 0) {
                    searchResults.innerHTML = '<div class="search-empty">検索結果が見つかりません</div>';
                    return;
                }

                const html = results.map(result => `
                    <div class="search-result">
                        <a href="${result.url}" class="search-result__link">
                            <div class="search-result__title">${result.title}</div>
                            <div class="search-result__description">${result.description}</div>
                        </a>
                    </div>
                `).join('');

                searchResults.innerHTML = html;
            }
        }
    }

    // 画像プレビュー
    function initImagePreview() {
        const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');

        imageInputs.forEach(input => {
            const previewId = input.dataset.preview;
            const previewElement = document.getElementById(previewId);

            if (previewElement) {
                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = (e) => {
                            previewElement.src = e.target.result;
                            previewElement.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        previewElement.style.display = 'none';
                    }
                });
            }
        });
    }

    // 自動保存機能
    function initAutoSave() {
        const forms = document.querySelectorAll('[data-autosave]');

        forms.forEach(form => {
            const interval = parseInt(form.dataset.autosave) || 30000; // デフォルト30秒
            let autoSaveTimeout;
            let isDirty = false;

            // フォームの変更を監視
            form.addEventListener('input', () => {
                isDirty = true;
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    if (isDirty) {
                        autoSave(form);
                    }
                }, interval);
            });

            function autoSave(form) {
                const formData = new FormData(form);
                const url = form.dataset.autosaveUrl || form.action;

                makeRequest(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                })
                .then(() => {
                    isDirty = false;
                    showAutoSaveIndicator();
                })
                .catch(error => {
                    console.error('Auto-save error:', error);
                });
            }

            function showAutoSaveIndicator() {
                // 自動保存インジケーターを表示
                const indicator = document.createElement('div');
                indicator.className = 'autosave-indicator';
                indicator.textContent = '自動保存しました';

                document.body.appendChild(indicator);

                setTimeout(() => {
                    indicator.remove();
                }, 3000);
            }
        });
    }

    // 通知システム
    function showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        notification.innerHTML = `
            <div class="notification__content">
                <span class="notification__message">${message}</span>
                <button class="notification__close">&times;</button>
            </div>
        `;

        document.body.appendChild(notification);

        // 閉じるボタンのイベント
        const closeBtn = notification.querySelector('.notification__close');
        closeBtn.addEventListener('click', () => {
            notification.remove();
        });

        // 自動で削除
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, duration);
        }
    }

    // ページ離脱警告
    function initPageUnloadWarning() {
        const forms = document.querySelectorAll('form:not([data-no-warning])');
        let hasUnsavedChanges = false;

        forms.forEach(form => {
            form.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });

            form.addEventListener('submit', () => {
                hasUnsavedChanges = false;
            });
        });

        window.addEventListener('beforeunload', (e) => {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });
    }

    // グローバル関数として公開
    window.AdminApp = {
        makeRequest,
        showNotification,
        confirmDelete,
        toggleSidebar,
        handleFormSubmit
    };

    // 初期化
    function init() {
        // イベントリスナーの登録
        document.addEventListener('click', (e) => {
            // メニュートグル
            if (e.target.matches('.menu-toggle')) {
                e.preventDefault();
                toggleSidebar();
            }

            // 削除確認
            if (e.target.matches('.confirm-delete')) {
                e.preventDefault();
                if (confirmDelete()) {
                    window.location.href = e.target.href;
                }
            }
        });

        // フォーム送信時のローディング表示
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form && !form.dataset.noLoading) {
                handleFormSubmit(form);
                // フォームは通常通り送信される（e.preventDefault()は呼ばない）
            }
        });

        // 各機能の初期化
        initTableSort();
        initBulkSelect();
        initLiveSearch();
        initImagePreview();
        initAutoSave();
        initPageUnloadWarning();

        console.log('管理画面初期化完了');
    }

    // DOM読み込み完了時に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();