<?php

/**
 * @var Auth $auth
 */

?>

<nav>
    <ul class="primary-navigation">
        <?php if (!$auth->isAuthenticated()): ?>
            <li class="action"><a href="./login.php">Login</a></li>
            <li class="action"><a href="./register.php">Register</a></li>

        <?php else: ?>
            <li class="user-balance" title="Your balance"><span class="icon">💰</span>
                <?= $auth->authenticatedUser()['balance'] ?>
            </li>
            <li class="user">
                <a href="./profile.php">
                    <?= $auth->authenticatedUser()['username'] ?>
                </a>
            </li>
            <li class="action"><a href="./logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>