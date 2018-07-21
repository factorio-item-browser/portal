<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

/**
 * The factory of the generic entity helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class GenericEntityHelperFactory
{
    /**
     * Creates the view helper.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return GenericEntityHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        return new GenericEntityHelper($urlHelper);
    }
}
