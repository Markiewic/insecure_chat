<?php

include '../core/database.php';
include '../core/authenticate.php';

if (!$user_id) {
    http_response_code(401);
    return;
}

$body = trim($_POST['body']);
$image_url = trim($_POST['image_url']);

if ($body || $image_url) {
    $connection->query("INSERT INTO contents.messages (sender, body, attached_image) VALUE ('$user_id', '$body', '$image_url');");
}

echo "$body $image_url";
