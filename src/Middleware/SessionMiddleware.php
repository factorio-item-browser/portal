<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use Blast\BaseUrl\BasePathHelper;
use DateTime;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Service\UserService;
use FactorioItemBrowser\Portal\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The middleware handling the session of the current user.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * The base path helper.
     * @var BasePathHelper
     */
    protected $basePathHelper;

    /**
     * The session manager.
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * Initializes the middleware.
     * @param BasePathHelper $basePathHelper
     * @param SessionManager $sessionManager
     * @param UserService $userService
     */
    public function __construct(
        BasePathHelper $basePathHelper,
        SessionManager $sessionManager,
        UserService $userService
    ) {
        $this->basePathHelper = $basePathHelper;
        $this->sessionManager = $sessionManager;
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
        $currentUser = $this->readUserFromRequest($request);

        $response = $handler->handle($request);

        $currentUser->setSessionData($this->sessionManager->getSessionData());
        $this->userService->persistCurrentUser();

        $response = $this->writeCookieToResponse($response);
        return $response;
    }

    /**
     * Reads and returns the current user from the specified request.
     * @param ServerRequestInterface $request
     * @return User
     */
    protected function readUserFromRequest(ServerRequestInterface $request): User
    {
        $sessionId = FigRequestCookies::get($request, Config::SESSION_COOKIE_NAME, '')->getValue();
        if (strlen($sessionId) > 0) {
            $user = $this->userService->getUserBySessionId($sessionId);
            if ($user instanceof User) {
                $user->setIsFirstVisit(false);
                $this->userService->setCurrentUser($user);
                $this->sessionManager->setSessionData($user->getSessionData());
            }
        }

        $this->userService->persistCurrentUser();
        return $this->userService->getCurrentUser();
    }

    /**
     * Writes the session cookie to the specified response.
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function writeCookieToResponse(ResponseInterface $response): ResponseInterface
    {
        $currentUser = $this->userService->getCurrentUser();
        if ($currentUser instanceof User) {
            $cookie = SetCookie::create(
                Config::SESSION_COOKIE_NAME,
                $this->userService->getCurrentUser()->getSessionId()
            );
            $cookie = $cookie->withExpires(new DateTime('+' . Config::SESSION_LIFETIME . ' seconds'))
                             ->withPath(($this->basePathHelper)('/'));
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;
    }
}
