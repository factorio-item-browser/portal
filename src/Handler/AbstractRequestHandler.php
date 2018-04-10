<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler;

use FactorioItemBrowser\Api\Client\Client\Client;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The abstract class of the request handlers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractRequestHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(Client $apiClient, TemplateRendererInterface $templateRenderer)
    {
        $this->apiClient = $apiClient;
        $this->templateRenderer = $templateRenderer;
    }
}