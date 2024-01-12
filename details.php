<?php

declare(strict_types=1);

require_once 'lib/utils.php';

// if id is not set or is not 13 characters long -> redirect to index.php
$cardId = isset($_GET['id']) && strlen($_GET['id']) === 13 ? $_GET['id'] : null;

if (is_null($cardId)) {
    redirect('index.php');
}
require_once 'storage/CardStorage.php';

$cardStorage = new CardStorage();
$card = $cardStorage->findById($cardId);

# if card is not found -> redirect to index.php
if (is_null($card)) {
    redirect('index.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $card['name'] ?> | IKémon
    </title>

    <link rel="stylesheet" href="resources/styles/details.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="primary-header text-2xl">
        <h1><a href="./index.php">IKémon</a> >
            <?= $card['name'] ?>
        </h1>
    </header>

    <main id="content">
        <div id="details">
            <?php if ($card['image'] !== ''): ?>
                <div class="pokemon-img clr-<?= $card['type'] ?>">
                    <img src="<?= $card['image'] ?>" alt="Picture of <?= $card['name'] ?>" width="475" height="475">
                </div>
            <?php endif; ?>

            <div class="info">
                <h2>
                    <?= $card['name'] ?>
                </h2>
                <?php if ($card['description'] !== ''): ?>
                    <div class="description">
                        <?= $card['description'] ?>
                    </div>
                <?php endif; ?>

                <span class="card-type"><span class="icon">🏷</span> Type:
                    <?= $card['type'] ?>
                </span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health:
                        <?= $card['hp'] ?>
                    </div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack:
                        <?= $card['attack'] ?>
                    </div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense:
                        <?= $card['defense'] ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require 'resources/views/_footer.php'; ?>
</body>

</html>