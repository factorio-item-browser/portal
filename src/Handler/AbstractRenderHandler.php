<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler;

use FactorioItemBrowser\Api\Client\Client\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The abstract class of handlers rendering some kind of page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractRenderHandler implements RequestHandlerInterface
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

    /**
     * Handles the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    abstract public function handle(ServerRequestInterface $request): ResponseInterface;

    /**
     * Renders the Not Found page.
     * @return ResponseInterface
     */
    protected function renderNotFoundPage(): ResponseInterface
    {
        return new HtmlResponse($this->templateRenderer->render('error::404'), 404);
    }

    /**
     * Renders the error of a paginated list request.
     * @return ResponseInterface
     */
    protected function renderPaginatedListError(): ResponseInterface
    {
        return new JsonResponse([
            'content' => $this->templateRenderer->render('error::page', [
                'layout' => false
            ])
        ]);
    }
}