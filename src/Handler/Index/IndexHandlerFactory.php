<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Index;

use FactorioItemBrowser\Api\Client\Client\Client;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The factory of the index handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class IndexHandlerFactory
{
    /**
     * Creates the request handler.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return IndexHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);

        return new IndexHandler($apiClient, $templateRenderer);
    }
}