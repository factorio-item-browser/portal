<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Settings;

use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use FactorioItemBrowser\Portal\Session\Container\SettingsSessionContainer;
use FactorioItemBrowser\Portal\View\Helper\SettingsHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the settings save handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsSaveHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SettingsSaveHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var ApiClientInterface $apiClient */
        $apiClient = $container->get(ApiClientInterface::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);
        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var SettingsHelper $settingsHelper */
        $settingsHelper = $helperPluginManager->get(SettingsHelper::class);
        /* @var SettingsSessionContainer $settingsSessionContainer */
        $settingsSessionContainer = $container->get(SettingsSessionContainer::class);
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);
        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        return new SettingsSaveHandler(
            $apiClient,
            $userService->getCurrentUser(),
            $settingsHelper,
            $settingsSessionContainer,
            $sidebarEntityService,
            $urlHelper
        );
    }
}
