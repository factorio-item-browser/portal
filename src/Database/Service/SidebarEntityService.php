<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Api\Client\Entity\GenericEntity;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Repository\SidebarEntityRepository;

/**
 * The service class of the sidebar entity database table.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntityService extends AbstractDatabaseService
{
    /**
     * The database user service.
     * @var UserService
     */
    protected $userService;

    /**
     * The repository of the sidebar entities.
     * @var SidebarEntityRepository
     */
    protected $sidebarEntityRepository;

    /**
     * SidebarEntityService constructor.
     * @param EntityManager $entityManager
     * @param UserService $userService
     */
    public function __construct(EntityManager $entityManager, UserService $userService)
    {
        parent::__construct($entityManager);
        $this->userService = $userService;
    }


    /**
     * Initializes the repositories needed by the service.
     * @param EntityManager $entityManager
     * @return $this
     */
    protected function initializeRepositories(EntityManager $entityManager)
    {
        $this->sidebarEntityRepository = $entityManager->getRepository(SidebarEntity::class);
        return $this;
    }

    /**
     * Adds an entity to the sidebar.
     * @param GenericEntity $entity
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(GenericEntity $entity)
    {
        $user = $this->userService->getCurrentUser();
        $sidebarEntity = $this->getExistingSidebarEntity($user, $entity);
        if (!$sidebarEntity instanceof SidebarEntity) {
            $sidebarEntity = $this->createNewSidebarEntity($user, $entity);
            $user->getSidebarEntities()->add($sidebarEntity);
            $this->entityManager->persist($sidebarEntity);
        }

        $sidebarEntity->setLabel($entity->getLabel())
                      ->setDescription($entity->getDescription())
                      ->setLastViewTime(new DateTime());

        $this->entityManager->flush($sidebarEntity);
        $this->sidebarEntityRepository->cleanUnpinnedEntities($user, Config::SIDEBAR_UNPINNED_ENTITIES);

        // @todo LayoutParams
        return $this;
    }

    /**
     * Returns an already existing sidebar entity to the specified one.
     * @param User $user
     * @param GenericEntity $entity
     * @return SidebarEntity|null
     */
    protected function getExistingSidebarEntity(User $user, GenericEntity $entity): ?SidebarEntity
    {
        $result = null;
        foreach ($user->getSidebarEntities() as $sidebarEntity) {
            if ($sidebarEntity->getType() === $entity->getType() && $sidebarEntity->getName() === $entity->getName()) {
                $result = $sidebarEntity;
                break;
            }
        }
        return $result;
    }

    /**
     * Creates a new sidebar entity for the specified one.
     * @param User $user
     * @param GenericEntity $entity
     * @return SidebarEntity
     */
    protected function createNewSidebarEntity(User $user, GenericEntity $entity): SidebarEntity
    {
        $result = new SidebarEntity($user);
        $result->setType($entity->getType())
               ->setName($entity->getName());

        return $result;
    }

    /**
     * Pins the specified entity to the sidebar.
     * @param SidebarEntity $sidebarEntity
     * @return $this
     */
    public function pin(SidebarEntity $sidebarEntity)
    {
        if ($sidebarEntity->getPinnedPosition() === 0) {
            $newPosition = $this->sidebarEntityRepository->getMaxPinnedPosition($sidebarEntity->getUser()) + 1;
            $sidebarEntity->setPinnedPosition($newPosition);
            $this->entityManager->flush($sidebarEntity);
        }
        return $this;
    }

    /**
     * Unpins the specified entity from the sidebar.
     * @param SidebarEntity $sidebarEntity
     * @return $this
     */
    public function unpin(SidebarEntity $sidebarEntity)
    {
        if ($sidebarEntity->getPinnedPosition() > 0) {
            $sidebarEntity->setPinnedPosition(0);
            $this->entityManager->flush($sidebarEntity);

            $this->sidebarEntityRepository->cleanUnpinnedEntities(
                $this->userService->getCurrentUser(),
                Config::SIDEBAR_UNPINNED_ENTITIES
            );
        }
        return $this;
    }
}