<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Sidebar;

use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The abstract class of the sidebar request handlers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractSidebarRequestHandler implements RequestHandlerInterface
{
    /**
     * The sidebar entity database service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * The current user.
     * @var User
     */
    protected $currentUser;

    /**
     * AbstractSidebarRequestHandler constructor.
     * @param SidebarEntityService $sidebarEntityService
     * @param User $currentUser
     */
    public function __construct(SidebarEntityService $sidebarEntityService, User $currentUser)
    {
        $this->sidebarEntityService = $sidebarEntityService;
        $this->currentUser = $currentUser;
    }

    /**
     * Returns the sidebar entity from the current user with the specified id.
     * @param int $sidebarEntityId
     * @return SidebarEntity|null
     */
    protected function getSidebarEntityById(int $sidebarEntityId): ?SidebarEntity
    {
        $result = null;
        foreach ($this->currentUser->getSidebarEntities() as $sidebarEntity) {
            if ($sidebarEntity->getId() === $sidebarEntityId) {
                $result = $sidebarEntity;
                break;
            }
        }
        return $result;
    }
}