<?php

$token = $connection->real_escape_string($_COOKIE['auth_token']);

$query = $connection->query("SELECT `user_id`, `id` FROM `authentication`.`sessions`
        WHERE `token` = '$token' AND `is_open` = 1;");
$session = $query->fetch_object();

$user = null;
$user_id = null;
$session_id = null;

if (isset($session)) {
    $user_id = $session->user_id;
    $session_id = $session->id;

    $query = $connection->query("SELECT * FROM `authentication`.`users`
        WHERE `id` = '$user_id';");
    $user = $query->fetch_object();

    $token = bin2hex(random_bytes(16));
    $connection->query("UPDATE `authentication`.`sessions` 
        SET `token` = '$token'
        WHERE `id` = '$session_id';");
    setcookie("auth_token", $token, 0, '/', ".example.com");
}
