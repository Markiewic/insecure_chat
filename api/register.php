<?php

include "../core/database.php";

$name = trim($connection->real_escape_string(htmlspecialchars($_POST['name'])));
$login = trim($connection->real_escape_string(htmlspecialchars($_POST['login'])));
$password = trim($connection->real_escape_string(htmlspecialchars($_POST['password'])));

if (!($name && $login && $password)) {
    http_response_code(400);
    header('Location: /register/');
    return;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$query = $connection->query("
    SELECT * FROM `authentication`.`users`
    WHERE login = '$login';
");

$existing_user = $query->fetch_object();

if (isset($existing_user)) {
    http_response_code(400);
    header('Location: /register?alert=exists');
    return;
}

$connection->query("
    INSERT INTO `authentication`.`users`
    (name, login, password_hash)
    VALUES ('$name', '$login', '$password_hash');
");

$user_id = $connection->insert_id;
$token = bin2hex(random_bytes(16));
$connection->query("INSERT INTO `authentication`.`sessions` 
            (user_id, token) VALUE ('$user_id', '$token');");
header('Location: /chat/');
setcookie("auth_token", $token, 0, '/', ".example.com");