<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Repository\UserRepository;

/**
 * The service class of the user database table.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class UserService extends AbstractDatabaseService
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
     * Initializes the repositories needed by the service.
     * @param EntityManager $entityManager
     * @return $this
     */
    protected function initializeRepositories(EntityManager $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
        return $this;
    }

    /**
     * Returns the user with the specified session ID.
     * @param string $sessionId
     * @return User|null
     */
    public function getBySessionId(string $sessionId): ?User
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
     * Creates a new user
     * @return User
     */
    public function createNewUser(): User
    {
        $user = new User();
        $user->setEnabledModNames(['base'])
             ->setSessionId($this->generateSessionId());

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
        return $user;
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
     * Persists the current user into the database.
     * @return $this
     */
    public function persistCurrentUser()
    {
        if ($this->currentUser instanceof User) {
            $this->entityManager->persist($this->currentUser);
            $this->entityManager->flush($this->currentUser);
        }
        return $this;
    }

    /**
     * Returns a hash of the current user's settings.
     * @return string
     */
    public function getSettingsHash()
    {
        return hash('crc32b', json_encode([
            $this->currentUser->getApiAuthorizationToken(),
            $this->currentUser->getLocale(),
            $this->currentUser->getRecipeMode()
        ]));
    }

    /**
     * Cleans up old users of which the sessions have timed out.
     * @return $this
     */
    public function cleanup()
    {
        $this->userRepository->cleanup();
        return $this;
    }
}
