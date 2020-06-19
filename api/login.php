<?php

include "../core/database.php";
include "../core/settings.php";

$login = $_POST['login'];
$password = $_POST['password'];

$max_attempt_count = 5;
$attempt_debounce = 3600;

function get_client_ip()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (isset($login) && isset($password)) {
    $login = strtolower($login);

    $allow_attempt = false;
    if ($auth_limit) {
        $now = (new DateTime())->getTimestamp();
        $ip = get_client_ip();
        $query = $connection->query("SELECT *, UNIX_TIMESTAMP(last_attempt_at) last_attempt_at 
        FROM `authentication`.`auth_attempts`
        WHERE `ip_address` = '$ip';");
        $attempt = $query->fetch_object();

        if (
            !isset($attempt) ||
            (($now - $attempt->last_attempt_at) > $attempt_debounce) ||
            ($attempt->attempts_count < $max_attempt_count)
        ) {
            $allow_attempt = true;
        } else {
            $allow_attempt = false;
        }
    } else {
        $allow_attempt = true;
    }

    if ($allow_attempt) {
        $query = $connection->query("SELECT `id`, `password_hash` FROM `authentication`.`users` 
            WHERE `login` = '$login';");

        $user = $query->fetch_object();
        if (isset($user) && password_verify($password, $user->password_hash)) {
            $user_id = $user->id;
            $token = bin2hex(random_bytes(16));
            $connection->query("INSERT INTO `authentication`.`sessions` 
                (user_id, token) VALUE ('$user_id', '$token');");
            header('Location: /chat/');
            setcookie("auth_token", $token, 0, '/', ".example.com");
        } else {
            if (isset($attempt)) {
                $current_attempt_count = ($attempt->attempts_count % $max_attempt_count) + 1;
                $connection->query("UPDATE `authentication`.`auth_attempts`
                    SET `attempts_count` = '$current_attempt_count',
                    `last_attempt_at` = CURRENT_TIMESTAMP()
                    WHERE ip_address = '$ip';");
            } else {
                $connection->query("INSERT INTO `authentication`.`auth_attempts`
                    (`ip_address`) VALUE ('$ip');");
            }
            http_response_code(401);
            header("Location: /login?alert=wrong");
        }
    } else {
        http_response_code(401);
        header("Location: /login?alert=limit");
    }
}
