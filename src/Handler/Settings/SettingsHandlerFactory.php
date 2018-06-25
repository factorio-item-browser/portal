<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Settings;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Session\Container\SettingsSessionContainer;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the settings handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SettingsHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var SettingsSessionContainer $settingsSessionContainer */
        $settingsSessionContainer = $container->get(SettingsSessionContainer::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);

        return new SettingsHandler($apiClient, $settingsSessionContainer, $templateRenderer);
    }
}