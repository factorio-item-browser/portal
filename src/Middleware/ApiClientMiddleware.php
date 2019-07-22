<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Service\UserService;
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
     * @var ApiClientInterface
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
     * @param ApiClientInterface $apiClient
     * @param TemplateRendererInterface $templateRenderer
     * @param UserService $userService
     */
    public function __construct(
        ApiClientInterface $apiClient,
        TemplateRendererInterface $templateRenderer,
        UserService $userService
    ) {
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
        $currentUser = $this->userService->getCurrentUser();
        $this->initializeApiClient($currentUser);

        try {
            $response = $handler->handle($request);
            $this->finalizeApiClient($currentUser);
        } catch (ApiClientException $e) {
            $response = $this->createErrorResponse();
        }

        return $response;
    }

    /**
     * Initializes the API client to be used.
     * @param User $currentUser
     */
    protected function initializeApiClient(User $currentUser): void
    {
        $this->apiClient->setLocale($currentUser->getLocale());
        $this->apiClient->setEnabledModNames($currentUser->getEnabledModNames());
        $this->apiClient->setAuthorizationToken($currentUser->getApiAuthorizationToken());
    }

    /**
     * Finalizes the API client after it has been used.
     * @param User $currentUser
     */
    protected function finalizeApiClient(User $currentUser): void
    {
        $currentUser->setApiAuthorizationToken($this->apiClient->getAuthorizationToken());
    }

    /**
     * Creates an error response in case the API client failed.
     * @return ResponseInterface
     */
    protected function createErrorResponse(): ResponseInterface
    {
        return new HtmlResponse($this->templateRenderer->render('error::503'), 503);
    }
}
