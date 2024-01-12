<?php

declare(strict_types=1);

require_once 'lib/_init.php';
require_once 'storage/CardStorage.php';
require_once 'enums/AccountTab.php';

$auth = new Auth(new UserStorage());
$cardStorage = new CardStorage();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $cardID = $_POST['id'];
    $auth->sellCard($cardID, $cardStorage);

    $currentTab = AccountTab::Cards->value;
    redirect("profile.php?tab=$currentTab");
}
