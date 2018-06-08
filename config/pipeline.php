<?php

declare(strict_types=1);

/**
 * The file setting up the pipeline.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Blast\BaseUrl\BaseUrlMiddleware;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware;
use Zend\Expressive\Router\Middleware\RouteMiddleware;
use Zend\Stratigility\Middleware\ErrorHandler;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->pipe(ErrorHandler::class);
    $app->pipe(Middleware\CleanupMiddleware::class);

    $app->pipe(BaseUrlMiddleware::class);
    $app->pipe(RouteMiddleware::class);
    $app->pipe(ServerUrlMiddleware::class);
    $app->pipe(UrlHelperMiddleware::class);
    $app->pipe(MethodNotAllowedMiddleware::class);
    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);

    $app->pipe(Middleware\SessionMiddleware::class);
    $app->pipe(Middleware\LocaleMiddleware::class);
    $app->pipe(Middleware\ApiClientMiddleware::class);

    $app->pipe(BodyParamsMiddleware::class);
    $app->pipe(Middleware\MetaDataRequestMiddleware::class);
    $app->pipe(Middleware\LayoutMiddleware::class);

    $app->pipe(DispatchMiddleware::class);
    $app->pipe(NotFoundHandler::class);
};
