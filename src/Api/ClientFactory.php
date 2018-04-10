<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Api;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Client\Options;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the API client.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ClientFactory implements FactoryInterface
{
    /**
     * Creates the API client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return Client
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $clientConfig = $config['factorio-item-browser']['client'];

        $options = new Options();
        $options->setAgent($clientConfig['agent'])
                ->setAccessKey($clientConfig['accessKey'])
                ->setApiUrl($clientConfig['apiUrl'])
                ->setTimeout($clientConfig['timeout']);

        return new Client($options);
    }
}