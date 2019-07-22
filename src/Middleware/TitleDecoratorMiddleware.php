<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Helper\HeadTitle;

/**
 * The middleware decorating the page title.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class TitleDecoratorMiddleware implements MiddlewareInterface
{
    /**
     * The head title helper.
     * @var HeadTitle
     */
    protected $headTitleHelper;

    /**
     * The translator.
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Initializes the middleware.
     * @param HeadTitle $headTitleHelper
     * @param TranslatorInterface $translator
     */
    public function __construct(
        HeadTitle $headTitleHelper,
        TranslatorInterface $translator
    ) {
        $this->headTitleHelper = $headTitleHelper;
        $this->translator = $translator;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->headTitleHelper->setSeparator(' - ');
        $this->headTitleHelper->append($this->translator->translate('meta title'));

        return $handler->handle($request);
    }
}
