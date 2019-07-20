<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * The abstract class of the database services.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractDatabaseService
{
    /**
     * The doctrine entity manager.
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Initializes the service.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->initializeRepositories($entityManager);
    }

    /**
     * Initializes the repositories needed by the service.
     * @param EntityManagerInterface $entityManager
     * @return $this
     */
    abstract protected function initializeRepositories(EntityManagerInterface $entityManager);
}
