<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
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
     * The repository of the sidebar entities.
     * @var SidebarEntityRepository
     */
    protected $sidebarEntityRepository;

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
}