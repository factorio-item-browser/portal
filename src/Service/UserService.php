<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Service;

use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Repository\UserRepository;

/**
 * The service handling the users.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class UserService
{
    /**
     * The repository of the users.
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * The current user.
     * @var User
     */
    protected $currentUser;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->currentUser = new User($this->generateSessionId());
    }

    /**
     * Generates a new session ID for the current session.
     * @return string
     */
    protected function generateSessionId(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $result = '';
        for ($i = 0; $i < 32; ++$i) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }
        return $result;
    }

    /**
     * Returns the user with the specified session ID.
     * @param string $sessionId
     * @return User|null
     */
    public function getUserBySessionId(string $sessionId): ?User
    {
        return $this->userRepository->findBySessionId($sessionId);
    }

    /**
     * Sets the current user.
     * @param User $currentUser
     */
    public function setCurrentUser(User $currentUser): void
    {
        $this->currentUser = $currentUser;
    }

    /**
     * Returns the current user.
     * @return User
     */
    public function getCurrentUser(): User
    {
        return $this->currentUser;
    }

    /**
     * Persists the current user into the database.
     */
    public function persistCurrentUser(): void
    {
        $this->userRepository->persist($this->currentUser);
    }
}
