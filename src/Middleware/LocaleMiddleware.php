<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\I18n\Translator\Translator;

/**
 * The middleware for detecting the locale of the current user.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LocaleMiddleware implements MiddlewareInterface
{
    /**
     * The translator.
     * @var Translator
     */
    protected $translator;

    /**
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * The locales of the portal.
     * @var array|string[]
     */
    protected $locales;

    /**
     * Initializes the middleware.
     * @param Translator $translator
     * @param UserService $userService
     * @param array|string[] $locales
     */
    public function __construct(Translator $translator, UserService $userService, array $locales)
    {
        $this->translator = $translator;
        $this->userService = $userService;
        $this->locales = $locales;
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

        $locale = $currentUser->getLocale();
        if ($locale === '') {
            $locale = $this->detectLocaleFromRequest($request);
            $currentUser->setLocale($locale);
        }
        $this->translator->setLocale($locale)
                         ->setFallbackLocale(Config::DEFAULT_LOCALE);

        return $handler->handle($request);
    }

    /**
     * Detects the preferred locale to use from the request.
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function detectLocaleFromRequest(ServerRequestInterface $request): string
    {
        $acceptLanguage = $request->getHeaderLine('Accept-Language');
        foreach (explode(',', $acceptLanguage) as $locale) {
            $pos = strpos($locale, ';');
            if ($pos !== false) {
                $locale = substr($locale, 0, $pos);
            }
            $locale = trim($locale);
            if (isset($this->locales[$locale])) {
                return $locale;
            }
        }

        return Config::DEFAULT_LOCALE;
    }
}
