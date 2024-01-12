<?php

/**
 * @var Auth $auth
 * @var CardStorage $cardStorage
 */
?>

<div id="card-list-data">
    <div id="card-list">
        <?php
        $cards = $auth->authenticatedUser()['cards'];
        foreach ($cards as $cardID):
            # get user's card from cardStorage
            $card = $cardStorage->findById($cardID);
            ?>
            <article class="pokemon-card" data-sellable=<?= $auth->authorize(['user']) ? 'true' : 'false' ?>>
                <?php require '_card-data.php'; ?>

                <?php if ($auth->authorize(['user'])): ?>
                    <form class="sell-form" action="./sell-card.php" method="post" novalidate>
                        <input type="hidden" name="id" value="<?= $cardID ?>">
                        <button type="submit" class="sell">Sell
                            <span class="card-price"><span class="icon">💰</span>
                                <?= $card['price'] * 0.9 ?>
                            </span>
                        </button>
                    </form>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>