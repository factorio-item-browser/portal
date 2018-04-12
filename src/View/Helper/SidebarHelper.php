<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Api\Client\Entity\GenericEntity;
use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use FactorioItemBrowser\Portal\Database\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for the sidebar.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarHelper extends AbstractHelper
{
    /**
     * The current user.
     * @var User
     */
    protected $currentUser;

    /**
     * Initializes the view helper.
     * @param User $currentUser
     */
    public function __construct(User $currentUser)
    {
        $this->currentUser = $currentUser;
    }


    /**
     * Renders the pinned entities of the current user.
     * @return string
     */
    public function renderPinnedEntities(): string
    {
        $result = '';
        foreach ($this->currentUser->getPinnedSidebarEntities() as $sidebarEntity) {
            $result .= $this->renderEntity($sidebarEntity);
        }
        return $result;
    }

    /**
     * Renders the pinned entities of the current user.
     * @return string
     */
    public function renderUnpinnedEntities(): string
    {
        $result = '';
        foreach ($this->currentUser->getUnpinnedSidebarEntities() as $sidebarEntity) {
            $result .= $this->renderEntity($sidebarEntity);
        }
        return $result;
    }

    /**
     * Renders the specified sidebar entity.
     * @param SidebarEntity $sidebarEntity
     * @return string
     */
    public function renderEntity(SidebarEntity $sidebarEntity): string
    {
        $genericEntity = new GenericEntity();
        $genericEntity->setType($sidebarEntity->getType())
                      ->setName($sidebarEntity->getName())
                      ->setLabel($sidebarEntity->getLabel())
                      ->setDescription($sidebarEntity->getDescription());

        return $this->view->render('helper::sidebar/entity', [
            'entity' => $sidebarEntity,
            'genericEntity' => $genericEntity
        ]);
    }
}