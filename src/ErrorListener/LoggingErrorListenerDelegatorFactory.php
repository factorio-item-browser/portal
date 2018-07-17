<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\ErrorListener;

use Interop\Container\ContainerInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

/**
 * The delegator factory injecting the logging error listener into the error handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LoggingErrorListenerDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * Creates and delegates the logging error listener.
     * @param  ContainerInterface $container
     * @param  string $name
     * @param  callable $callback
     * @param  null|array $options
     * @return ErrorHandler
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /* @var ErrorHandler $errorHandler */
        $errorHandler = $callback();

        if ($container->has('logger.factorio-item-browser')) {
            /* @var LoggerInterface $logger */
            $logger = $container->get('logger.factorio-item-browser');
            $errorHandler->attachListener(new LoggingErrorListener($logger));
        }

        return $errorHandler;
    }
}
