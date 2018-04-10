<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Expressive\ZendView\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the javascript config view helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class JavascriptConfigFactory implements FactoryInterface {
    /**
     * Creates the view helper.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return JavascriptConfig
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
//        /* @var UserService $userService */
//        $userService = $container->get(UserService::class);
        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var UrlHelper $urlHelper */
//        $urlHelper = $helperPluginManager->get(UrlHelper::class);

        return new JavascriptConfig('abc');
    }
}