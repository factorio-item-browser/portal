<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Service\UserService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The middleware filling the layout params helper with required values.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LayoutParamsMiddleware implements MiddlewareInterface
{
    /**
     * The layout params helper.
     * @var LayoutParamsHelper
     */
    protected $layoutParamsHelper;

    /**
     * The meta session container.
     * @var MetaSessionContainer
     */
    protected $metaSessionContainer;

    /**
     * The database user service.
     * @var UserService
     */
    protected $userService;

    /**
     * Initializes the middleware.
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param MetaSessionContainer $metaSessionContainer
     * @param UserService $userService
     */
    public function __construct(
        LayoutParamsHelper $layoutParamsHelper,
        MetaSessionContainer $metaSessionContainer,
        UserService $userService
    ) {
        $this->layoutParamsHelper = $layoutParamsHelper;
        $this->metaSessionContainer = $metaSessionContainer;
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
        $this->layoutParamsHelper->setSettingsHash($currentUser->getSettingsHash())
                                 ->setNumberOfAvailableMods($this->metaSessionContainer->getNumberOfAvailableMods())
                                 ->setNumberOfEnabledMods($this->metaSessionContainer->getNumberOfEnabledMods());

        return $handler->handle($request);
    }
}
