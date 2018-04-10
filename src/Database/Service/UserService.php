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
}