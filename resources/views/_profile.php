<?php

/**
 * @var Auth $auth
 */

?>

<div class="profile">
    <div>
        <h3>Username</h3>
        <p>
            <?= $auth->authenticatedUser()['username'] ?>
        </p>
    </div>

    <div>
        <h3>Email</h3>
        <p>
            <?= $auth->authenticatedUser()['email'] ?>
        </p>
    </div>

    <div>
        <h3>Balance</h3>
        <p><span class="icon">💰</span>
            <?= $auth->authenticatedUser()['balance'] ?>
        </p>
    </div>
</div>