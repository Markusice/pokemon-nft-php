<?php

/**
 * @var array $card
 */

?>

<?php if ($card['image'] !== ''): ?>
    <div class="pokemon-img clr-<?= $card['type'] ?>">
        <img src="<?= $card['image'] ?>" alt="Picture of <?= $card['name'] ?>" loading="lazy" width="475" height="475">
    </div>
<?php endif; ?>

<div class="details">
    <h2><a href="./details.php?id=<?= $card['id'] ?>">
            <?= $card['name'] ?>
        </a></h2>
    <span class="card-type"><span class="icon">🏷</span>
        <?= $card['type'] ?>
    </span>
    <span class="attributes">
        <span class="card-hp"><span class="icon">❤</span>
            <?= $card['hp'] ?>
        </span>
        <span class="card-attack"><span class="icon">⚔</span>
            <?= $card['attack'] ?>
        </span>
        <span class="card-defense"><span class="icon">🛡</span>
            <?= $card['defense'] ?>
        </span>
    </span>
</div>