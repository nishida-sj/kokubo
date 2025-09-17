<?php
// 管理画面ログインページ
?>

<div class="login-form">
    <h2 class="login-form__title">ログイン</h2>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert--error">
            <ul class="alert__list">
                <?php foreach ($errors as $error): ?>
                    <li><?= h($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('admin/login') ?>" method="POST" class="form">
        <?= Csrf::field() ?>

        <div class="form-group">
            <label for="username" class="form-label">ユーザー名</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?= h($username ?? '') ?>"
                   class="form-input"
                   required
                   autofocus
                   placeholder="admin">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">パスワード</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-input"
                   required
                   placeholder="パスワードを入力">
        </div>

        <div class="form-submit">
            <button type="submit" class="btn btn--primary btn--block">
                ログイン
            </button>
        </div>
    </form>

    <div class="login-help">
        <p class="login-help__text">
            ログインでお困りの場合は、システム管理者にお問い合わせください。
        </p>
    </div>
</div>