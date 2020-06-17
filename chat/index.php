<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./chat.css">
    <script src="./chat.js"></script>
</head>
<body>
<div class="box">
    <div class="header">
        <div class="name">Алан</div>
        <div class="actions">
            <a href="/configuration">Настройки</a>
            <a href="/logout">Выйти</a>
        </div>
    </div>
    <div class="chat">
        <div class="messages-box">
            <div class="message">
                <div class="name">
                    Алан
                </div>
                <div class="body">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                        when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div>
                <div class="attachment">

                </div>
            </div>
        </div>
        <div class="message-form">
            <form id="message-form-instance">
                <button type="button" id="form-attach-button">Прикрепить</button>
                <input type="text" name="body" placeholder="Сообщение">
                <input type="hidden" name="body">
                <button type="submit">Отправить</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>