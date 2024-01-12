<?php

declare(strict_types=1);

function redirect(string $page): void
{
    header("Location: $page");
    exit();
}

function validateRegistration(array $post, array &$data, array &$errors): bool
{
    if ('' !== $username = trim($post['username'])) {
        if (strlen($username) > 32) {
            $errors['username'] = 'Name must be shorter than 32 characters!';
        }
    } else {
        $errors['username'] = 'Name is required!';
    }

    if ('' !== $email = trim($post['email'])) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address!';
        }
    } else {
        $errors['email'] = 'Email is required!';
    }

    if ('' === $password = trim($post['password'])) {
        $errors['password'] = 'Password is required!';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long!';
    }

    if ('' === $passwordConfirmation = trim($post['passwordConfirmation'])) {
        $errors['passwordConfirmation'] = 'Password again is required!';
    } elseif ($password !== $passwordConfirmation) {
        $errors['passwordConfirmation'] = 'Password again does not match!';
    }

    if (count($errors) === 0) {
        $data = $post;
        return true;
    }
    return false;
}

function validateLogin(array $post, array &$data, array &$errors): bool
{
    if ('' === trim($post['username'])) {
        $errors['username'] = 'Username is required!';
    }
    if ('' === trim($post['password'])) {
        $errors['password'] = 'Password is required!';
    }

    if (count($errors) === 0) {
        $data = $post;
        return true;
    }
    return false;
}

function validateNewCard(Auth $auth, array $post, array $types, array &$data, array &$errors): bool
{
    if ('' !== $name = trim($post['name'])) {
        if (strlen($name) > 32) {
            $errors['name'] = 'Name must be shorter than 32 characters!';
        }
    } else {
        $errors['name'] = 'Name is required!';
    }

    if (!in_array($post['type'], $types)) {
        $errors['type'] = 'Type is required!';
    }

    if ('' === $hp = trim($post['hp'])) {
        $errors['hp'] = 'HP is required!';
    } elseif ($hp <= 0) {
        $errors['hp'] = 'HP must be a positive number!';
    }

    if ('' === $attack = trim($post['attack'])) {
        $errors['attack'] = 'Attack is required!';
    } elseif ($attack <= 0) {
        $errors['attack'] = 'Attack must be a positive number!';
    }

    if ('' === $defense = trim($post['defense'])) {
        $errors['defense'] = 'Defense is required!';
    } elseif ($defense <= 0) {
        $errors['defense'] = 'Defense must be a positive number!';
    }

    if ('' === $price = trim($post['price'])) {
        $errors['price'] = 'Price is required!';
    } elseif ($price <= 0) {
        $errors['price'] = 'Price must be a positive number!';
    }

    $image = trim($post['image']);
    if ('' !== $image && !filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = 'Image must be a valid URL!';
    }

    if (count($errors) === 0) {
        $data = $post;
        $data['description'] = $post['description'] ?? '';
        $data['image'] = $post['image'] ?? '';
        $data['owner'] = $auth->authenticatedUser()['id'];
        return true;
    }
    return false;
}

function paginate(array $cards, int $cardsPerPage, int $currentPage): array
{
    $offset = $currentPage * $cardsPerPage - $cardsPerPage;
    return array_slice($cards, $offset, $cardsPerPage);
}
