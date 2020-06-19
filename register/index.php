<?php
$alert = $_GET['alert'] ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>
    <link rel="stylesheet" href="./register.css">
</head>
<body>
<div class="box">
    <?php if ($alert == 'exists'): ?>
        <p>Пользователь с таким логином уже существует</p>
    <?php endif; ?>
    <form action="/api/register.php" method="post">
        <label for="name">Имя:</label>
        <br>
        <input type="text" id="name" name="name">
        <br>
        <label for="login">Логин:</label>
        <br>
        <input type="text" id="login" name="login">
        <br>
        <label for="password">Пароль:</label>
        <br>
        <input type="password" id="password" name="password">
        <br>
        <div class="actions">
            <button type="submit">Отправить</button>
            <a href="/login/">Вход</a>
        </div>
    </form>
</div>
</body>
</html>
