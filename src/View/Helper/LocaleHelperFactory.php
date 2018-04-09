<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the locale helper class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 * @todo Read locale from user.
 */
class LocaleHelperFactory implements FactoryInterface {
    /**
     * Creates the view helper.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return LocaleHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
//        /* @var UserService $userService */
//        $userService = $container->get(UserService::class);

        $config = $container->get('config');
        $enabledLocales = array_keys(array_filter($config['factorio-item-browser']['portal']['locales']));

        return new LocaleHelper($enabledLocales, 'en' /*$userService->getCurrentUser()->getLocale() */);
    }
}