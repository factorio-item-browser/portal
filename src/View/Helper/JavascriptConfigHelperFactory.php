<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the javascript config view helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class JavascriptConfigHelperFactory implements FactoryInterface
{
    /**
     * Creates the view helper.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return JavascriptConfigHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var AssetPathHelper $assetPathHelper */
        $assetPathHelper = $helperPluginManager->get(AssetPathHelper::class);
        /* @var LayoutParamsHelper $layoutParamsHelper */
        $layoutParamsHelper = $helperPluginManager->get(LayoutParamsHelper::class);

        return new JavascriptConfigHelper(
            $assetPathHelper,
            $layoutParamsHelper->getSettingsHash(),
            $urlHelper
        );
    }
}
