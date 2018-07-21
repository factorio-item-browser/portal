<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session\Container;

use FactorioItemBrowser\Portal\Session\SessionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The abstract factory of the session containers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AbstractSessionContainerFactory implements FactoryInterface
{
    /**
     * Creates the session container.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return AbstractSessionContainer
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var AbstractSessionContainer $sessionContainer */
        $sessionContainer = new $requestedName();

        /* @var SessionManager $sessionManager */
        $sessionManager = $container->get(SessionManager::class);
        $sessionManager->initializeContainer($sessionContainer);

        return $sessionContainer;
    }
}
