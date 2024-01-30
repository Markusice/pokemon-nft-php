<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page'])) {
    require_once 'lib/_init.php';
    require_once 'storage/CardStorage.php';

    $userStorage = new UserStorage();
    $auth = new Auth(new UserStorage());

    $cardStorage = new CardStorage();
    $cards = $cardStorage->findAll();

    if (isset($_POST['filter'])) {
        $filterType = $_POST['filter'];

        require_once 'lib/types.php';

        /**
         * @var array $types
         */

        if (isTypeValid($filterType, $types)) {
            $cards = getCardsByType($cardStorage, $filterType, $types);
        }
    }

    /**
     * @var int $cardsPerPage
     * @var float $totalPages
     */
    require_once 'lib/cards-page.php';

    $currentPage = (int) $_POST['page'];
    $cardsToShow = paginate($cards, $cardsPerPage, $currentPage);

    ob_start();
    require 'resources/views/_pokemon-cards.php';
    $responseHTML = ob_get_clean();

    $hasNext = $currentPage < $totalPages;
    $hasPrev = $currentPage > 1;

    $response = [
        'responseHTML' => $responseHTML,
        'hasNext' => $hasNext,
        'hasPrev' => $hasPrev,
    ];
    echo json_encode($response);
}
