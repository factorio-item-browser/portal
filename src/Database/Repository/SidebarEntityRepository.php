<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Repository;

use Doctrine\ORM\EntityRepository;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;

/**
 * The repository of the sidebar entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntityRepository extends EntityRepository
{
    /**
     * Returns the maximal pinned position currently in use for the specified player.
     * @param User $user
     * @return int
     */
    public function getMaxPinnedPosition(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('MAX(s.pinnedPosition)')
                     ->andWhere('s.user = :userId')
                     ->setParameter('userId', $user->getId());

        return intval($queryBuilder->getQuery()->getSingleScalarResult());
    }

    /**
     * Cleans unpinned entities if there are too many.
     * @param User $user
     * @param int $numberOfUnpinnedEntities
     * @return $this
     */
    public function cleanUnpinnedEntities(User $user, int $numberOfUnpinnedEntities)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('s.id')
                     ->andWhere('s.user = :userId')
                     ->andWhere('s.pinnedPosition = 0')
                     ->addOrderBy('s.lastViewTime', 'DESC')
                     ->setFirstResult($numberOfUnpinnedEntities)
                     ->setParameter('userId', $user->getId());

        $ids = [];
        foreach ($queryBuilder->getQuery()->getResult() as $row) {
            $ids[] = intval($row['id']);
        }

        if (count($ids) > 0) {
            $queryBuilder = $this->_em->createQueryBuilder();
            $queryBuilder->delete(SidebarEntity::class, 's')
                         ->andWhere('s.id IN (:ids)')
                         ->setParameter('ids', array_values($ids));
            $queryBuilder->getQuery()->execute();
        }

        return $this;
    }
}
