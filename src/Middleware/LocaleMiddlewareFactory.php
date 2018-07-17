<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the locale middleware.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LocaleMiddlewareFactory implements FactoryInterface
{
    /**
     * Creates the middleware.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return LocaleMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $enabledLocales = array_keys(array_filter($config['factorio-item-browser']['portal']['locales']));

        /* @var UserService $userService */
        $userService = $container->get(UserService::class);
        /* @var Translator $translator */
        $translator = $container->get(TranslatorInterface::class);

        return new LocaleMiddleware($enabledLocales, $userService, $translator);
    }
}
