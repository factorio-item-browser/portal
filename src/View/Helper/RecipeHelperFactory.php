<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the recipe helper class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeHelperFactory implements FactoryInterface
{
    /**
     * Creates the view helper.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return RecipeHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new RecipeHelper($userService->getCurrentUser());
    }
}
