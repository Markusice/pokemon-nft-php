<?php

require_once 'vendor/Auth.php';
require_once 'storage/UserStorage.php';
require_once 'lib/utils.php';

session_start([
    'cookie_lifetime' => 86400,
]);
