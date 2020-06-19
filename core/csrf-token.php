<?php

function generateCSRFToken(){
    $csrf_token = bin2hex(random_bytes(16));
    setcookie("csrf_token", $csrf_token, 0, '/', ".example.com");
    return $csrf_token;
}

function checkCSRFToken($token){
    return $_COOKIE['csrf_token'] == $token;
}