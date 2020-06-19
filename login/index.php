<?php

$alert = $_GET['alert'];

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход</title>
    <link rel="stylesheet" href="./login.css">
</head>
<body>
<div class="box">
    <?php if ($alert == 'wrong'): ?>
        <p>Неверный логин или пароль</p>
    <?php endif; ?>

    <?php if ($alert == 'limit'): ?>
        <p>Совершено максимальное количество неудачных попыток входа, попробуйте снова через час</p>
    <?php endif; ?>

    <form action="/api/login.php" method="post">
        <label for="login">Логин:</label>
        <br>
        <input type="text" id="login" name="login">
        <br>
        <label for="password">Пароль:</label>
        <br>
        <input type="password" id="password" name="password">
        <br>
        <div class="actions">
            <button type="submit">Войти</button>
            <a href="/register/">Регистрация</a>
        </div>
    </form>
</div>
</body>
</html>
