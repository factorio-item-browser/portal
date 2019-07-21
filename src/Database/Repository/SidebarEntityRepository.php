<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Repository;

use Doctrine\ORM\EntityManagerInterface;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;

/**
 * The repository of the sidebar entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntityRepository
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
     * Returns the maximal pinned position currently in use for the specified player.
     * @param User $user
     * @return int
     */
    public function getMaxPinnedPosition(User $user): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('MAX(se.pinnedPosition)')
                     ->from(SidebarEntity::class, 'se')
                     ->andWhere('s.user = :userId')
                     ->setParameter('userId', $user->getId());

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * Cleans unpinned entities if there are too many.
     * @param User $user
     * @param int $numberOfUnpinnedEntities
     */
    public function cleanUnpinnedEntities(User $user, int $numberOfUnpinnedEntities): void
    {
        $sidebarEntityIds = $this->findOverflowingUnpinnedEntityIds($user, $numberOfUnpinnedEntities);
        if ($sidebarEntityIds > 0) {
            $this->removeIds($sidebarEntityIds);
        }
    }

    /**
     * Returns the ids of unpinned entities, which are overflowing the limit.
     * @param User $user
     * @param int $numberOfUnpinnedEntities
     * @return array|int[]
     */
    protected function findOverflowingUnpinnedEntityIds(User $user, int $numberOfUnpinnedEntities): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('se.id')
                     ->from(SidebarEntity::class, 'se')
                     ->andWhere('se.user = :userId')
                     ->andWhere('se.pinnedPosition = 0')
                     ->addOrderBy('se.lastViewTime', 'DESC')
                     ->setFirstResult($numberOfUnpinnedEntities)
                     ->setParameter('userId', $user->getId());

        $result = [];
        foreach ($queryBuilder->getQuery()->getResult() as $data) {
            $result[] = (int) $data['id'];
        }
        return $result;
    }

    /**
     * Removes the sidebar entities with the specified isd.
     * @param array|int[] $sidebarEntityIds
     */
    protected function removeIds(array $sidebarEntityIds): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(SidebarEntity::class, 'se')
                     ->andWhere('se.id IN (:sidebarEntityIds)')
                     ->setParameter('sidebarEntityIds', array_values($sidebarEntityIds));

        $queryBuilder->getQuery()->execute();
    }
}
