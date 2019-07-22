<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The middleware occasionally cleaning up not-needed data.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class CleanupMiddleware implements MiddlewareInterface
{
    /**
     * The user repository.
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * CleanupMiddleware constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->getRandomNumber() === 42) {
            $this->userRepository->cleanup();
        }
        return $response;
    }

    /**
     * Returns a random number.
     * @return int
     */
    protected function getRandomNumber(): int
    {
        return mt_rand(0, Config::CLEANUP_FACTOR);
    }
}
