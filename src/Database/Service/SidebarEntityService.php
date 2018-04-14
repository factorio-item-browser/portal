<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\GenericEntity;
use FactorioItemBrowser\Api\Client\Request\Generic\GenericDetailsRequest;
use FactorioItemBrowser\Api\Client\Response\Generic\GenericDetailsResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Repository\SidebarEntityRepository;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;

/**
 * The service class of the sidebar entity database table.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntityService extends AbstractDatabaseService
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The layout params helper.
     * @var LayoutParamsHelper
     */
    protected $layoutParamsHelper;

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
     * @param Client $apiClient
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param UserService $userService
     */
    public function __construct(
        EntityManager $entityManager,
        Client $apiClient,
        LayoutParamsHelper $layoutParamsHelper,
        UserService $userService
    )
    {
        parent::__construct($entityManager);
        $this->apiClient = $apiClient;
        $this->layoutParamsHelper = $layoutParamsHelper;
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

        $this->layoutParamsHelper->setNewSidebarEntity($sidebarEntity);
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

    /**
     * Refreshes the sidebar entities of the current user, removing any entities which are no longer available with the
     * currently enabled mods.
     * @return $this
     */
    public function refresh()
    {
        if ($this->userService->getCurrentUser()->getSidebarEntities()->count() > 0) {
            $detailsRequest = new GenericDetailsRequest();
            /* @var SidebarEntity[] $entities */
            $entities = [];
            foreach ($this->userService->getCurrentUser()->getSidebarEntities() as $sidebarEntity) {
                $entities[$sidebarEntity->getType() . '/' . $sidebarEntity->getName()] = $sidebarEntity;
                $detailsRequest->addEntity($sidebarEntity->getType(), $sidebarEntity->getName());
            }

            /* @var GenericDetailsResponse $detailsResponse */
            $detailsResponse = $this->apiClient->send($detailsRequest);

            // Update the labels and descriptions of entities which are still available.
            foreach ($detailsResponse->getEntities() as $entity) {
                $key = $entity->getType() . '/' . $entity->getName();
                if (isset($entities[$key])) {
                    $entities[$key]->setLabel($entity->getLabel())
                        ->setDescription($entity->getDescription());
                    unset($entities[$key]);
                }
            }

            // Remove any entities which are no longer available.
            foreach ($entities as $entity) {
                $this->userService->getCurrentUser()->getSidebarEntities()->removeElement($entity);
                $this->entityManager->remove($entity);
            }

            $this->entityManager->flush();
        }
        return $this;
    }
}