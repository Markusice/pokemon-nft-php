<?php

declare(strict_types=1);

require_once 'lib/_init.php';
require_once 'lib/types.php';
require_once 'storage/CardStorage.php';

$userStorage = new UserStorage();
$auth = new Auth($userStorage);

$cardStorage = new CardStorage();
$cards = $cardStorage->findAll();

/**
 * @var array $types
 */

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filterType']) && in_array($_GET['filterType'], $types)) {
    $cards = $cardStorage->findMany(fn($card) => $card['type'] === $_GET['filterType']); # filter cards by type
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) { # if user wants to buy a card
    $cardID = $_POST['id'];
    $errors = [];

    if (!$auth->buyCard($cardID, $cardStorage)) {
        $errors['buy'] = 'You don\'t have enough money or you already exceeded the maximum number of cards.';
    } else {
        redirect('index.php'); # refresh page
    }
}

/**
 * @var int $cardsPerPage
 * @var float $totalPages
 */

require_once 'lib/cards-page.php';

if (isset($_GET['page']) && $_GET['page'] > 1 && $_GET['page'] <= $totalPages) # if user wants to change page
    $currentPage = (int) $_GET['page'];
else
    $currentPage = 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | IKémon</title>

    <link rel="stylesheet" href="./resources/styles/main.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="./resources/scripts/pagination.js" type="module"></script>
</head>

<body>
    <header class="primary-header text-2xl">
        <h1><a href="./index.php">IKémon</a> > Home</h1>

        <?php require 'resources/views/_nav.php'; ?>
    </header>

    <main id="content" class="grid gap-y-6 auto-rows-max">
        <form action="" method="get" class="w-max p-2 rounded-lg" novalidate>
            <div class="grid grid-cols-[repeat(3,_max-content)] gap-x-4 items-center">
                <label for="filterType" class="tracking-wide">Filter by type</label>

                <select name="filterType" id="filterType" class="text-sm rounded-lg block w-max p-2.5">
                    <option value="">--Type to filter--</option>

                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type ?>" <?= (isset($_GET['filterType']) && $_GET['filterType'] === $type) ? 'selected' : '' ?>>
                            <?= ucfirst($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="secondary-btn tracking-wide rounded-xl w-24 p-2 text-center">
                    Filter
                </button>
            </div>
        </form>

        <?php if (isset($errors['buy'])): ?>
            <span class="error">
                <?= $errors['buy'] ?>
            </span>
        <?php endif; ?>

        <?php
        $canPaginate = $totalPages > 1;
        if ($canPaginate):
            $hasNext = $currentPage < $totalPages;
            $hasPrev = $currentPage > 1;
            ?>
            <nav aria-label="pagination">
                <ul class="pagination flex gap-x-1">
                    <li>
                        <a href="" class="page-link <?= $hasPrev ? '' : 'disabled' ?>" data-page="prev">
                            <span aria-hidden="true">«</span>
                            <span class="visuallyhidden">previous set of pages</span>
                        </a>
                    </li>

                    <?php foreach (range(1, (int) $totalPages) as $page): ?>
                        <li>
                            <a href="" class="page-link" data-page="<?= $page ?>" <?php if ($page === $currentPage): ?>
                                    aria-current="page" <?php endif; ?>>
                                <span class="visuallyhidden">page </span>
                                <?= $page ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <li>
                        <a href="" class="page-link <?= $hasNext ? '' : 'disabled' ?>" data-page="next">
                            <span class="visuallyhidden">next set of pages</span>
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

        <div id="card-list-data">
            <div id="card-list">
                <?php
                $cardsToShow = paginate($cards, $cardsPerPage, $currentPage);
                require 'resources/views/_pokemon-cards.php';
                ?>
            </div>
        </div>
    </main>

    <?php require 'resources/views/_footer.php'; ?>
</body>

</html>