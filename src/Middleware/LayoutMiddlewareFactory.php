<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use FactorioItemBrowser\Portal\View\Helper\SidebarHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Helper\HeadTitle;
use Zend\View\HelperPluginManager;

/**
 * The factory of the layout middleware.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LayoutMiddlewareFactory
{
    /**
     * Creates the middleware.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return LayoutMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var MetaSessionContainer $metaSessionContainer */
        $metaSessionContainer = $container->get(MetaSessionContainer::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        /* @var TranslatorInterface $translator */
        $translator = $container->get(TranslatorInterface::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var HeadTitle $headTitleHelper */
        $headTitleHelper = $helperPluginManager->get('headTitle');
        /* @var LayoutParamsHelper $layoutParamsHelper */
        $layoutParamsHelper = $helperPluginManager->get(LayoutParamsHelper::class);
        /* @var SidebarHelper $sidebarHelper */
        $sidebarHelper = $helperPluginManager->get(SidebarHelper::class);

        return new LayoutMiddleware(
            $metaSessionContainer,
            $templateRenderer,
            $translator,
            $userService,
            $headTitleHelper,
            $layoutParamsHelper,
            $sidebarHelper
        );
    }
}
