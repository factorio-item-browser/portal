<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the format helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class FormatHelperFactory implements FactoryInterface
{
    /**
     * Creates the format helper.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return FormatHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var TranslatorInterface $translator */
        $translator = $container->get(TranslatorInterface::class);

        return new FormatHelper($translator);
    }
}
