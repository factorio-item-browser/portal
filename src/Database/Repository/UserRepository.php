<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;

/**
 * The repository of the User entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class UserRepository extends EntityRepository
{
    /**
     * Finds the user with the specified session ID.
     * @param string $sessionId
     * @return User|null
     */
    public function findBySessionId(string $sessionId): ?User
    {
        $lifetime = new DateTime('-' . Config::SESSION_LIFETIME . ' seconds');

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->andWhere('u.sessionId = :sessionId')
                     ->andWhere('u.lastVisit >= :lifetime')
                     ->setParameter('sessionId', $sessionId)
                     ->setParameter('lifetime', $lifetime->format('Y-m-d H:i:s'));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Cleans up old users of which the sessions have timed out.
     * @return $this
     */
    public function cleanup()
    {
        $lifetime = new DateTime('-' . Config::SESSION_LIFETIME . ' seconds');
        $lifetimeShort = new DateTime('-' . Config::SESSION_LIFETIME_SHORT . ' seconds');

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->select('u.id')
                     ->orWhere('u.lastVisit < :lifetime')
                     ->orWhere('(u.isFirstVisit = 1 AND u.lastVisit < :lifetimeShort)')
                     ->setParameter('lifetime', $lifetime->format('Y-m-d H:i:s'))
                     ->setParameter('lifetimeShort', $lifetimeShort->format('Y-m-d H:i:s'));

        $userIds = [];
        foreach ($queryBuilder->getQuery()->getResult() as $row) {
            $userIds[] = $row['id'];
        }

        if (count($userIds) > 0) {
            $queryBuilder = $this->_em->createQueryBuilder();
            $queryBuilder->delete(SidebarEntity::class, 's')
                         ->andWhere('s.user IN (:userIds)')
                         ->setParameter('userIds', array_values($userIds));
            $queryBuilder->getQuery()->execute();

            $queryBuilder = $this->_em->createQueryBuilder();
            $queryBuilder->delete(User::class, 'u')
                         ->andWhere('u.id IN (:userIds)')
                         ->setParameter('userIds', array_values($userIds));
            $queryBuilder->getQuery()->execute();
        }

        return $this;
    }
}