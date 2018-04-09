<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Blast\BaseUrl\BasePathViewHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the asset path view helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AssetPathHelperFactory implements FactoryInterface
{
    /**
     * Creates the view helper.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return AssetPathHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var BasePathViewHelper $basePathHelper */
        $basePathHelper = $helperPluginManager->get(BasePathViewHelper::class);

        return new AssetPathHelper($config['version'], $basePathHelper);
    }
}