<?php

include "../core/database.php";
include "../core/authenticate.php";
include "../core/settings.php";
include "../core/csrf-token.php";

$csrf_t = $_GET['csrf_t'] ?? '';

if ($csrf_token) {
    if (!checkCSRFToken($csrf_t)) {
        http_response_code(401);
        header('Location: /chat/');
        return;
    }
}

$allowed = $user && $user->is_admin;

if (!$allowed) {
    http_response_code(401);
    header('Location: /chat/');
    return;
}

$connection->query("TRUNCATE TABLE `contents`.`messages`;");
header('Location: /chat/');
