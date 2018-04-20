<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Traits;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The trait for creating a soft 404 page when a handler cannot find the wanted data.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
trait NotFoundResponseTrait
{
    /**
     * Creates a 404 Not Found response for the current request.
     * @param TemplateRendererInterface $templateRenderer
     * @return ResponseInterface
     */
    protected function createNotFoundResponse(TemplateRendererInterface $templateRenderer): ResponseInterface
    {
        $response = new HtmlResponse($templateRenderer->render('error::404'));
        $response = $response->withStatus(404);
        return $response;
    }
}