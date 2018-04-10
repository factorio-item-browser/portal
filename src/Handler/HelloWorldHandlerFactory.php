<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 *
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class HelloWorldHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return HelloWorldHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);

        return new HelloWorldHandler($templateRenderer);
    }
}