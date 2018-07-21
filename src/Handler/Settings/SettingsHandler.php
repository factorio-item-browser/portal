<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Settings;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use FactorioItemBrowser\Portal\Session\Container\SettingsSessionContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The handler showing available settings to be changed.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsHandler extends AbstractRenderHandler
{
    /**
     * The session container of the settings.
     * @var SettingsSessionContainer
     */
    protected $settingsSessionContainer;

    /**
     * SettingsHandler constructor.
     * @param Client $apiClient
     * @param SettingsSessionContainer $settingsSessionContainer
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        Client $apiClient,
        SettingsSessionContainer $settingsSessionContainer,
        TemplateRendererInterface $templateRenderer
    ) {
        parent::__construct($apiClient, $templateRenderer);
        $this->settingsSessionContainer = $settingsSessionContainer;
    }

    /**
     * Handles the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new HtmlResponse($this->templateRenderer->render('portal::settings/settings', [
            'showSuccessMessage' => $this->settingsSessionContainer->getShowSuccessMessage()
        ]));
        $this->settingsSessionContainer->reset();
        return $response;
    }
}
