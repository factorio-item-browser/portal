<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the javascript config view helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class JavascriptConfigFactory implements FactoryInterface
{
    /**
     * Creates the view helper.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return JavascriptConfig
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);
        // @todo Use actual settings hash.
        return new JavascriptConfig($config['version'], 'abc', $urlHelper);
    }
}