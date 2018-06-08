<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Client\Client;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the recipe machine page handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeMachinePageHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return RecipeMachinePageHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);

        return new RecipeMachinePageHandler($apiClient, $templateRenderer);
    }
}