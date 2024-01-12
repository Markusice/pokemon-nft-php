<?php

declare(strict_types=1);

/**
 * @var Auth $auth
 * @var array $data
 * @var array $errors
 * @var bool $success
 * @var array $types
 */

?>

<?php if (!$auth->authorize(['admin'])):
    redirect('profile.php');
else: ?>
    <h3 class="font-bold">Add New Card</h3>

    <form action="" method="post" novalidate>
        <div class="grid gap-y-4 gap-x-8 grid-cols-2">
            <div class="grid gap-y-2">
                <label for="name" class="tracking-wide">Card name</label>
                <input type="text" name="name" id="name" autocomplete="off" value="<?= $_POST['name'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-60 p-2.5">

                <?php if (isset($errors['name'])): ?>
                    <span class="error">
                        <?= $errors['name'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="hp" class="tracking-wide">HP</label>
                <input type="number" name="hp" id="hp" value="<?= $_POST['hp'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-40 p-2.5">

                <?php if (isset($errors['hp'])): ?>
                    <span class="error">
                        <?= $errors['hp'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="type" class="tracking-wide">Type</label>
                <select name="type" id="type" class="text-sm rounded-lg block max-w-60 p-2.5">
                    <option value="">--Please choose a type--</option>

                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type ?>" <?= (isset($_POST['type']) && $_POST['type'] === $type) ? 'selected' : '' ?>>
                            <?= ucfirst($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if (isset($errors['type'])): ?>
                    <span class="error">
                        <?= $errors['type'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="attack" class="tracking-wide">Attack</label>
                <input type="number" name="attack" id="attack" value="<?= $_POST['attack'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-40 p-2.5">

                <?php if (isset($errors['attack'])): ?>
                    <span class="error">
                        <?= $errors['attack'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="defense" class="tracking-wide">Defense</label>
                <input type="number" name="defense" id="defense" value="<?= $_POST['defense'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-60 p-2.5">

                <?php if (isset($errors['defense'])): ?>
                    <span class="error">
                        <?= $errors['defense'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="price" class="tracking-wide">Price</label>
                <input type="number" name="price" id="price" value="<?= $_POST['price'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-40 p-2.5">

                <?php if (isset($errors['price'])): ?>
                    <span class="error">
                        <?= $errors['price'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="description" class="tracking-wide">Description</label>
                <input type="text" name="description" id="description" value="<?= $_POST['description'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-80 p-2.5">

                <?php if (isset($errors['description'])): ?>
                    <span class="error">
                        <?= $errors['description'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid gap-y-2">
                <label for="image" class="tracking-wide">Image</label>
                <input type="url" name="image" id="image" value="<?= $_POST['image'] ?? '' ?>"
                    class="text-gray-900 text-sm rounded-lg block max-w-80 p-2.5">

                <?php if (isset($errors['image'])): ?>
                    <span class="error">
                        <?= $errors['image'] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-[repeat(2,max-content)] gap-x-3 mt-2">
                <button type="submit" class="primary-btn tracking-wide rounded-xl p-3">
                    Create new card
                </button>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $success): ?>
                    <p class="tracking-wide p-3">Successfully created new card!</p>
                <?php endif; ?>
            </div>
        </div>
    </form>

<?php endif; ?>