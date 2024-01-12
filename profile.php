<?php

declare(strict_types=1);

require_once 'lib/_init.php';

$userStorage = new UserStorage();
$auth = new Auth($userStorage);

if (!$auth->isAuthenticated()) {
    redirect('login.php');
}

require_once 'enums/AccountTab.php';
require_once 'storage/CardStorage.php';
require_once 'lib/types.php';

/**
 * @var array $types
 */

$currentTab = $_GET['tab'] ?? AccountTab::Profile->value;
$cardStorage = new CardStorage();

if ($currentTab === AccountTab::AdminPanel->value && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    $errors = [];

    if ($success = validateNewCard($auth, $_POST, $types, $data, $errors)) {
        $cardID = $cardStorage->add($data);
        $auth->addCard($cardID);
        unset($_POST); // clear data for input values
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= ucfirst($currentTab) ?> | IKémon
    </title>

    <link rel="stylesheet" href="./resources/styles/profile.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="primary-header text-2xl">
        <h1><a href="./index.php">IKémon</a> >
            <?= ucfirst($currentTab) ?>
        </h1>

        <nav>
            <ul class="primary-navigation">
                <li class="user-balance" title="Your balance"><span class="icon">💰</span>
                    <?= $auth->authenticatedUser()['balance'] ?>
                </li>
                <li class="user">
                    <?= $auth->authenticatedUser()['username'] ?>
                </li>
                <li class="action"><a href="./logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main id="content">
        <div class="grid w-[70%] ms-auto me-auto">
            <h2 class="text-3xl mb-2">Account</h2>
            <p class="text-lg opacity-80">Details of your account</p>

            <ul class="tabs mt-4 flex p-1 gap-x-1 rounded-md w-max">
                <li <?php if ($currentTab === AccountTab::Profile->value): ?> class="active" <?php endif; ?>>
                    <a href="./profile.php">Profile</a>
                </li>

                <li <?php if ($currentTab === AccountTab::Cards->value): ?> class="active" <?php endif; ?>>
                    <a href="./profile.php?tab=<?= AccountTab::Cards->value ?>">Cards</a>
                </li>

                <?php if ($auth->authorize(['admin'])): ?>
                    <li <?php if ($currentTab === AccountTab::AdminPanel->value): ?> class="active" <?php endif; ?>>
                        <a href="./profile.php?tab=<?= AccountTab::AdminPanel->value ?>">Admin
                            Panel</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="data rounded-md p-5 mt-6">
                <?php switch ($currentTab) {
                    case AccountTab::Profile->value:
                        require 'resources/views/_profile.php';
                        break;
                    case AccountTab::Cards->value:
                        require 'resources/views/_cards.php';
                        break;
                    case AccountTab::AdminPanel->value:
                        require 'resources/views/_admin-panel.php';
                        break;
                } ?>
            </div>
        </div>
    </main>

    <?php require 'resources/views/_footer.php'; ?>
</body>

</html>