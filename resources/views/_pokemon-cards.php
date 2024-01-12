<?php

/**
 * @var Auth $auth
 * @var UserStorage $userStorage
 * @var array $cardsToShow
 */

$admin = $userStorage->findOne(['username' => 'admin']);

?>

<?php foreach ($cardsToShow as $card): ?>
    <article class="pokemon-card" data-noimage=<?= $card['image'] === '' ? 'true' : 'false' ?>>
        <?php require '_card-data.php'; ?>

        <?php if ($auth->authorize(['user']) && $card['owner'] === $admin['id']): ?>
            <form class="buy-form" action="" method="post" novalidate>
                <input type="hidden" name="id" value="<?= $card['id'] ?>">
                <button type="submit" class="buy">
                    <span class="card-price"><span class="icon">💰</span>
                        <?= $card['price'] ?>
                    </span>
                </button>
            </form>
        <?php endif; ?>
    </article>
<?php endforeach; ?>