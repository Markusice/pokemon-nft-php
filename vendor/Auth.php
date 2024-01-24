<?php

declare(strict_types=1);

class Auth
{
    private IStorage $userStorage;
    private ?array $user = null;
    private int $startingBalance = 1500;
    private int $maxCards = 5;

    public function __construct(IStorage $user_storage)
    {
        $this->userStorage = $user_storage;

        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
    }

    public function updateSession(): void
    {
        $_SESSION['user'] = $this->user;
    }

    public function buyCard(string $cardID, CardStorage $cardStorage): bool
    {
        $card = $cardStorage->findById($cardID);

        if (!$this->canBuyCard($card)) {
            return false;
        }

        $this->addCard($cardID);
        $this->user['balance'] -= $card['price'];
        $this->updateSession();

        $admin = $this->userStorage->findOne(['username' => 'admin']);
        $admin['cards'] = array_values(array_filter($admin['cards'], fn($_cardID) => $_cardID !== $cardID)); # remove card from admin and reindex for json
        $this->userStorage->update($admin['id'], $admin);

        $card['owner'] = $this->user['id'];
        $cardStorage->update($cardID, $card);

        return true;
    }

    private function canBuyCard(array $card): bool
    {
        return $this->user['balance'] >= $card['price'] && count($this->user['cards']) < $this->maxCards;
    }

    public function addCard(string $cardID): void
    {
        $this->user['cards'][] = $cardID;
        $this->userStorage->update($this->user['id'], $this->user);
    }

    public function sellCard(string $cardID, CardStorage $cardStorage): void
    {
        $card = $cardStorage->findById($cardID);

        $this->removeCard($cardID);
        $this->user['balance'] += $card['price'] * 0.9;
        $this->updateSession();

        $admin = $this->userStorage->findOne(['username' => 'admin']);
        $admin['cards'][] = $cardID;
        $this->userStorage->update($admin['id'], $admin);

        $card['owner'] = $admin['id'];
        $cardStorage->update($cardID, $card);
    }

    private function removeCard(string $cardID): void
    {
        $this->user['cards'] = array_values(array_filter($this->user['cards'], fn($_cardID) => $_cardID !== $cardID)); # remove card from user and reindex for json
        $this->userStorage->update($this->user['id'], $this->user);
    }

    public function register(array $data): string|bool
    {
        if ($this->userStorage->findOne(['username' => $data['username']]) || $this->userStorage->findOne(['email' => $data['email']])) {
            return false;
        }
        $user = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'balance' => $this->startingBalance,
            'cards' => [],
            'roles' => ['user'],
        ];
        return $this->userStorage->add($user);
    }

    public function userExists(string $username): bool
    {
        $users = $this->userStorage->findOne(['username' => $username]);
        return !is_null($users);
    }

    public function authenticate(string $username, string $password): mixed
    {
        $users = $this->userStorage->findMany(function ($user) use ($username, $password) {
            return $user['username'] === $username &&
                password_verify($password, $user['password']);
        });
        return count($users) === 1 ? array_shift($users) : null;
    }

    public function isAuthenticated(): bool
    {
        return !is_null($this->user);
    }

    public function authorize(array $roles = []): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }
        foreach ($roles as $role) {
            if (in_array($role, $this->user['roles'])) {
                return true;
            }
        }
        return false;
    }

    public function login(array $user): void
    {
        $this->user = $user;
        $_SESSION['user'] = $user;
    }

    public function logout(): void
    {
        $this->user = null;
        unset($_SESSION['user']);
    }

    public function authenticatedUser(): array
    {
        return $this->user;
    }
}
