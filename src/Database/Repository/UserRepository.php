<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Repository;

use Doctrine\ORM\EntityRepository;
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
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->andWhere('u.sessionId = :sessionId')
                     ->setParameter('sessionId', $sessionId);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}