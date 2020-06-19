<?php

include "../core/database.php";
include "../core/authenticate.php";
include "../core/settings.php";
include "../core/csrf-token.php";

if ($csrf_token) {
    $csrf_t = generateCSRFToken();
}

$allowed = $user && $user->is_admin;

if (!$allowed) {
    http_response_code(401);
    header('Location: /chat/');
    return;
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Настройки</title>
    <link rel="stylesheet" href="./configuration.css">
</head>
<body>

<div class="box">
    <div class="actions">
        <?php if ($csrf_token): ?>
            <a href="/api/clearChat.php?csrf_t=<?= $csrf_t ?>">Очистить чат</a>
        <?php else: ?>
            <a href="/api/clearChat.php">Очистить чат</a>
        <?php endif; ?>
    </div>
    <br>
    <form action="/api/updateSettings.php" method="post">
        <input type="checkbox" id="csrf_token" name="csrf_token" <?= $csrf_token ? 'checked' : '' ?>>
        <label for="csrf_token">Требовать CSRF-токен</label>
        <br>
        <input type="checkbox" id="auth_limit" name="auth_limit" <?= $auth_limit ? 'checked' : '' ?>>
        <label for="auth_limit">Ограничить количетсво неудачных попыток аутентификации с одного IP до пяти за
            час</label>
        <br>
        <div class="actions">
            <button type="submit">Сохранить</button>
            <a href="/chat/">Вернуться в чат</a>
        </div>
    </form>
</div>
</body>
</html>
