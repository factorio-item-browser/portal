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
     * The default locale to use when everything else fails.
     */
    private const DEFAULT_LOCALE = 'en';

    /**
     * The user database service.
     * @var UserService
     */
    protected $userService;

    /**
     * The translator.
     * @var Translator
     */
    protected $translator;

    /**
     * The locales of the portal.
     * @var array|string[]
     */
    protected $locales;

    /**
     * Initializes the middleware.
     * @param UserService $userService
     * @param Translator $translator
     * @param array|string[] $locales
     */
    public function __construct(UserService $userService, Translator $translator, array $locales)
    {
        $this->userService = $userService;
        $this->translator = $translator;
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
        $newLocale = $this->userService->getCurrentUser()->getLocale();
        if (strlen($newLocale) === 0) {
            $newLocale = $this->detectLocaleFromRequest($request);
        }

        $this->userService->getCurrentUser()->setLocale($newLocale);
        $this->translator->setLocale($newLocale)
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
        $result = self::DEFAULT_LOCALE;
        $acceptLanguage = $request->getHeaderLine('Accept-Language');
        foreach (explode(',', $acceptLanguage) as $locale) {
            $pos = strpos($locale, ';');
            if ($pos !== false) {
                $locale = substr($locale, 0, $pos);
            }
            if (isset($this->locales[$locale])) {
                $result = $locale;
                break;
            }
        }
        return $result;
    }
}
