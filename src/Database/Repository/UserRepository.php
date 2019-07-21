<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Repository;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;

/**
 * The repository of the User entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class UserRepository
{
    /**
     * The entity manager.
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Initializes the repository.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Finds the user with the specified session ID.
     * @param string $sessionId
     * @return User|null
     */
    public function findBySessionId(string $sessionId): ?User
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('u')
                     ->from(User::class, 'u')
                     ->andWhere('u.sessionId = :sessionId')
                     ->andWhere('u.lastVisit >= :lifetime')
                     ->setParameter('sessionId', $sessionId)
                     ->setParameter('lifetime', $this->createLifetime(Config::SESSION_LIFETIME));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Creates the lifetime datetime.
     * @param int $numberOfSeconds
     * @return DateTimeInterface
     */
    protected function createLifetime(int $numberOfSeconds): DateTimeInterface
    {
        return new DateTime('-' . $numberOfSeconds . 'seconds');
    }

    /**
     * Cleans up old users of which the sessions have timed out.
     */
    public function cleanup(): void
    {
        $userIds = array_merge(
            $this->findUserWithTimedOutSessions($this->createLifetime(Config::SESSION_LIFETIME), false),
            $this->findUserWithTimedOutSessions($this->createLifetime(Config::SESSION_LIFETIME_SHORT), true)
        );

        if (count($userIds) > 0) {
            $this->removeIds($userIds);
        }
    }

    /**
     * Finds users with already timed out sessions.
     * @param DateTimeInterface $lifetime
     * @param bool $isFirstVisit
     * @return array
     */
    protected function findUserWithTimedOutSessions(DateTimeInterface $lifetime, bool $isFirstVisit): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('u.id')
                     ->from(User::class, 'u')
                     ->andWhere('u.lastVisit < :lifetime')
                     ->andWhere('u.isFirstVisit = :isFirstVisit')
                     ->setParameter('lifetime', $lifetime)
                     ->setParameter('isFirstVisit', $isFirstVisit);

        $result = [];
        foreach ($queryBuilder->getQuery()->getResult() as $data) {
            $result[] = (int) $data['id'];
        }
        return $result;
    }

    /**
     * Removes the user with the specified ids from the database.
     * @param array|int[] $userIds
     */
    protected function removeIds(array $userIds): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(SidebarEntity::class, 'se')
                     ->andWhere('se.user IN (:userIds')
                     ->setParameter('userIds', array_values($userIds));
        $queryBuilder->getQuery()->execute();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(User::class, 'u')
                     ->andWhere('u.id IN (:userIds)')
                     ->setParameter('userIds', array_values($userIds));
        $queryBuilder->getQuery()->execute();
    }

    /**
     * Persists the user entity into the database.
     * @param User $user
     */
    public function persist(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
