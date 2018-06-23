<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Settings;

use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * The handler showing the list of available settings to be changed.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsListHandler extends AbstractRenderHandler
{
    /**
     * Handles the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->templateRenderer->render('portal::settings/list'));
    }
}