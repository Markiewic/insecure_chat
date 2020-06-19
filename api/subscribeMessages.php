<?php

include '../core/database.php';
include '../core/authenticate.php';

if (!$user_id) {
    http_response_code(401);
    return;
}

header("Content-Type: text/event-stream");

$lastTime = round(microtime(true) * 1000);

while (true) {
    $query = $connection->query("
        select message.*, user.name name
            from contents.messages message
            join authentication.users user on user.id = message.sender
            where message.created_on > $lastTime;
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

//    echo "data:         select message.*, user.name name from contents.messages message join authentication.users user on user.id = message.sender where message.created_on > $lastTime\n\n";

    if (count($messages) > 0) {
        $messagesPlain = json_encode($messages);

        echo "event: message" . "\n\n";
        echo "data: $messagesPlain" . "\n\n";
    }

    ob_end_flush();
    flush();
    sleep(0.1);
}
