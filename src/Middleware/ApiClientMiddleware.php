<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * Initializes the middleware.
     * @param Client $apiClient
     * @param UserService $userService
     */
    public function __construct(Client $apiClient, UserService $userService)
    {
        $this->apiClient = $apiClient;
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
        $this->apiClient->setLocale($currentUser->getLocale())
                        ->setEnabledModNames($currentUser->getEnabledModNames())
                        ->setAuthorizationToken($currentUser->getApiAuthorizationToken());

        $response = $handler->handle($request);

        $this->userService->getCurrentUser()->setApiAuthorizationToken($this->apiClient->getAuthorizationToken());
        return $response;
    }
}