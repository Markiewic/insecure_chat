<?php

include "../core/database.php";
include "../core/authenticate.php";

if ($session_id) {
    $connection->query("
        UPDATE `authentication`.`sessions` 
        SET is_open = 0
        WHERE id = '$session_id';
    ");
    setcookie('auth_token', null, 0, '/', ".example.com");
}
header("Location: /login/");
