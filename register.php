<?php

declare(strict_types=1);

require_once 'lib/_init.php';

$userStorage = new UserStorage();
$auth = new Auth($userStorage);

if ($auth->isAuthenticated()) { # user already logged in
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    $errors = [];

    if (validateRegistration($_POST, $data, $errors)) {
        if (!$registeredUserID = $auth->register($data)) {
            $errors['global'] = 'Username or email already registered!';
        } else {
            $auth->login($userStorage->findById($registeredUserID));
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
    <title>Register | IKémon</title>

    <link rel="stylesheet" href="./resources/styles/register.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="primary-header text-2xl">
        <h1><a href="./index.php">IKémon</a> > Register</h1>
    </header>

    <main id="content" class="grid justify-center">
        <form action="" method="post" novalidate>
            <?php if (isset($errors['global'])): ?>
                <span class="error global">
                    <?= $errors['global'] ?>
                </span>
            <?php endif; ?>

            <div class="grid gap-y-4 px-4">
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
                    <label for="email" class="tracking-wider">Email:</label>
                    <input type="email" name="email" id="email" autocomplete="email"
                        value="<?= $_POST['email'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-60 p-2.5">
                    <?php if (isset($errors['email'])): ?>
                        <span class="error">
                            <?= $errors['email'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid gap-y-2">
                    <label for="password" class="tracking-wider">Password:</label>
                    <input type="password" name="password" id="password" autocomplete="new-password"
                        value="<?= $_POST['password'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-60 p-2.5">
                    <?php if (isset($errors['password'])): ?>
                        <span class="error">
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid gap-y-2">
                    <label for="passwordConfirmation" class="tracking-wider">Password again:</label>
                    <input type="password" name="passwordConfirmation" id="passwordConfirmation"
                        autocomplete="new-password" value="<?= $_POST['passwordConfirmation'] ?? '' ?>"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-60 p-2.5">
                    <?php if (isset($errors['passwordConfirmation'])): ?>
                        <span class="error">
                            <?= $errors['passwordConfirmation'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-[repeat(2,max-content)] gap-x-3 mt-2">
                    <button type="submit" class="primary-btn tracking-wide rounded-xl w-28 p-3">
                        Register
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