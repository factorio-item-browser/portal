<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Search;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the search query handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SearchQueryHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SearchQueryHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var LayoutParamsHelper $layoutParamsHelper */
        $layoutParamsHelper = $helperPluginManager->get(LayoutParamsHelper::class);

        return new SearchQueryHandler($apiClient, $layoutParamsHelper, $templateRenderer, $urlHelper);
    }
}
