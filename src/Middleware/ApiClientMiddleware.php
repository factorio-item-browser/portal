<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The middleware managing the API client.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ApiClientMiddleware implements MiddlewareInterface
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
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * Initializes the middleware.
     * @param Client $apiClient
     * @param TemplateRendererInterface $templateRenderer
     * @param UserService $userService
     */
    public function __construct(
        Client $apiClient,
        TemplateRendererInterface $templateRenderer,
        UserService $userService
    )
    {
        $this->apiClient = $apiClient;
        $this->templateRenderer = $templateRenderer;
        $this->userService = $userService;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $currentUser = $this->userService->getCurrentUser();
            $this->apiClient->setLocale($currentUser->getLocale())
                            ->setEnabledModNames($currentUser->getEnabledModNames())
                            ->setAuthorizationToken($currentUser->getApiAuthorizationToken());

            $response = $handler->handle($request);

            $this->userService->getCurrentUser()->setApiAuthorizationToken($this->apiClient->getAuthorizationToken());
        } catch (ApiClientException $e) {
            $response = new HtmlResponse($this->templateRenderer->render('error::503'), 503);
        }
        return $response;
    }
}