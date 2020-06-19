<?php

include "../core/database.php";
include "../core/authenticate.php";

$allowed = $user && $user->is_admin;

if (!$allowed) {
    http_response_code(401);
    header('Location: /chat/');
    return;
}

$csrf_token = (int)isset($_POST['csrf_token']);
$auth_limit = (int)isset($_POST['auth_limit']);

echo "
    INSERT INTO `configuration`.`settings` (`key`, `value`) value ('csrf_token', '$csrf_token') 
    ON DUPLICATE KEY UPDATE value = '$csrf_token';
    INSERT INTO `configuration`.`settings` (`key`, `value`) value ('auth_limit', '$auth_limit') 
    ON DUPLICATE KEY UPDATE value = '$auth_limit';
";

$connection->query("
    INSERT INTO `configuration`.`settings` (`key`, `value`) value ('csrf_token', '$csrf_token') ON DUPLICATE KEY UPDATE `value` = '$csrf_token';
");

$connection->query("
    INSERT INTO `configuration`.`settings` (`key`, `value`) value ('auth_limit', '$auth_limit') ON DUPLICATE KEY UPDATE `value` = '$auth_limit';
");

header('Location: /chat/');
