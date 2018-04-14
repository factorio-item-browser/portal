<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use DateTime;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Service\UserService;
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
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * The session manager.
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Initializes the middleware.
     * @param UserService $userService
     * @param SessionManager $sessionManager
     */
    public function __construct(UserService $userService, SessionManager $sessionManager)
    {
        $this->userService = $userService;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->readUserFromRequest($request);

        $response = $handler->handle($request);

        $this->userService->getCurrentUser()->setSessionData($this->sessionManager->getSessionData());
        $this->userService->persistCurrentUser();

        $response = $this->writeCookieToResponse($response);
        return $response;
    }

    /**
     * Reads the current user from the specified request.
     * @param ServerRequestInterface $request
     * @return $this
     */
    protected function readUserFromRequest(ServerRequestInterface $request)
    {
        $user = null;
        $sessionId = FigRequestCookies::get($request, Config::SESSION_COOKIE_NAME, '')->getValue();
        if (strlen($sessionId) > 0) {
            $user = $this->userService->getBySessionId($sessionId);
        }
        if (!$user instanceof User) {
            $user = $this->userService->createNewUser();
        }
        $user->setIsFirstVisit($sessionId !== $user->getSessionId());
        $this->userService->setCurrentUser($user);
        $this->sessionManager->setSessionData($user->getSessionData());
        return $this;
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
            $cookie = $cookie->withExpires(new DateTime('+'. Config::SESSION_LIFETIME . ' seconds'))
                             ->withPath('/');
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;

    }
}