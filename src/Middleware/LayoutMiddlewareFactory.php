<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
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
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var HeadTitle $headTitleHelper */
        $headTitleHelper = $helperPluginManager->get('headTitle');
        /* @var LayoutParamsHelper $layoutParamsHelper */
        $layoutParamsHelper = $helperPluginManager->get(LayoutParamsHelper::class);

        return new LayoutMiddleware($templateRenderer, $headTitleHelper, $layoutParamsHelper);
    }
}