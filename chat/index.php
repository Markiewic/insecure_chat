<?php

include '../core/database.php';
include '../core/authenticate.php';

if (!$user_id) {
    http_response_code(401);
    header('Location: /login/');
    return;
}

$query = $connection->query("
        select message.*, user.name name
            from contents.messages message
            join authentication.users user on user.id = message.sender;
    ");

$messages = array();
while ($messageScheme = $query->fetch_object()) {
    $lastTime = $messageScheme->created_on;
    $message = array(
        'name' => $messageScheme->name,
        'body' => $messageScheme->body,
        'createdOn' => (int)$messageScheme->created_on,
        'attachedImage' => $messageScheme->attached_image,
    );
    $messages[] = $message;
}


setlocale(LC_TIME, "ru_RU");

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Чат</title>
    <link rel="stylesheet" href="./chat.css">
    <script src="./chat.js"></script>
</head>
<body>
<div class="box">
    <div class="header">
        <div class="name"><?= $user->name ?></div>
        <div class="actions">
            <?php if ($user->is_admin == 1): ?>
                <a href="/configuration">Настройки</a>
            <?php endif; ?>
            <a href="/api/logout.php">Выйти</a>
        </div>
    </div>
    <div class="chat">
        <div class="messages-box" id="messages-box">
            <?php foreach ($messages as $message): ?>
            <div class="message">
                <div class="meta">
                    <div class="name">
                        <?= $message['name'] ?>
                    </div>
                    <div class="time">
                        <?= strftime("%d.%m.%Y, %H:%M:%S", $message['createdOn'] / 1000) ?>
                    </div>
                </div>
                <div class="body">
                    <p><?= $message['body'] ?></p>
                </div>
                <?php if ($message['attachedImage']): ?>
                    <div class="attachment">
                        <img src="<?= $message['attachedImage'] ?>">
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach ?>
        </div>
        <div class="message-form">
            <form id="message-form-instance" action="#">
                <button type="button" id="form-attach-button">Прикрепить</button>
                <button type="button" id="form-detach-button" hidden>Открепить</button>
                <input type="file" id="file-input" style="display: none">
                <textarea name="body" placeholder="Сообщение"></textarea>
                <input type="hidden" name="image-url">
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>