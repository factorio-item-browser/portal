<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use DateTime;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Service\UserService;
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
     * The name of the cookie to save the session ID in.
     */
    private const COOKIE_NAME = 'FIB';

    /**
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * Initializes the middleware.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
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
        $this->readUserFromRequest($request);

        $response = $handler->handle($request);
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
        $sessionId = FigRequestCookies::get($request, self::COOKIE_NAME, '')->getValue();
        if (strlen($sessionId) > 0) {
            $user = $this->userService->getBySessionId($sessionId);
        }
        if (!$user instanceof User) {
            $user = $this->userService->createNewUser();
        }
        $this->userService->setCurrentUser($user);
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
            $cookie = SetCookie::create(self::COOKIE_NAME, $this->userService->getCurrentUser()->getSessionId());
            $cookie = $cookie->withExpires(new DateTime('+1 month'))
                             ->withPath('/');
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;

    }
}