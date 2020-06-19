<?php

include "./database.php";

$csrf_token = false;
$auth_limit = false;

$query = $connection->query("SELECT `key`, `value` FROM `configuration`.`settings`;");

while ($setting = $query->fetch_object()) {
    if ($setting->key == 'csrf_token') {
        $csrf_token = (int)$setting->value;
    }
    if ($setting->key == 'auth_limit') {
        $auth_limit = (int)$setting->value;
    }
}
