<?php

declare(strict_types=1);

require_once 'lib/_init.php';

$auth = new Auth(new UserStorage());
$auth->logout();
redirect('index.php');
