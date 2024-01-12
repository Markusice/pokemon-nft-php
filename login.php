<?php

declare(strict_types=1);

require_once 'lib/_init.php';

$auth = new Auth(new UserStorage());

if ($auth->isAuthenticated()) { # user already logged in
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    $errors = [];

    if (validateLogin($_POST, $data, $errors)) {
        if (!$loggedInUser = $auth->authenticate($data['username'], $data['password'])) {
            $errors['global'] = 'Invalid password or username!';
        } else {
            $auth->login($loggedInUser);
            redirect('index.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | IKémon</title>

    <link rel="stylesheet" href="./resources/styles/login.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="primary-header text-2xl">
        <h1><a href="./index.php">IKémon</a> > Login</h1>
    </header>

    <main id="content" class="grid justify-center">
        <form action="" method="post" novalidate>
            <?php if (isset($errors['global'])): ?>
                <span class="error global">
                    <?= $errors['global'] ?>
                </span>
            <?php endif; ?>

            <div class="grid gap-y-4">
                <div class="grid gap-y-2">
                    <label for="username" class="tracking-wider">Username:</label>
                    <input type="text" name="username" id="username" value="<?= $_POST['username'] ?? '' ?>"
                        autocomplete="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-60 p-2.5">
                    <?php if (isset($errors['username'])): ?>
                        <span class="error">
                            <?= $errors['username'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid gap-y-2">
                    <label for="password" class="tracking-wider">Password:</label>
                    <input type="password" name="password" id="password" autocomplete="current-password"
                        value="<?= $_POST['password'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-60 p-2.5">
                    <?php if (isset($errors['password'])): ?>
                        <span class="error">
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-[repeat(2,max-content)] gap-x-3 mt-2">
                    <button type="submit" class="primary-btn tracking-wide rounded-xl w-28 p-3">
                        Login
                    </button>
                    <a href="./index.php" class="secondary-btn tracking-wide rounded-xl w-28 p-3 text-center">Home
                    </a>
                </div>
            </div>
        </form>
    </main>

    <?php require 'resources/views/_footer.php'; ?>
</body>

</html>