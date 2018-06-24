<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Settings;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\View\Helper\SettingsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;

/**
 * The handler saving the changed settings.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsSaveHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The currently logged in user.
     * @var User
     */
    protected $currentUser;

    /**
     * The settings view helper.
     * @var SettingsHelper
     */
    protected $settingsHelper;

    /**
     * The database sidebar entity service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * The URL helper.
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * SettingsSaveHandler constructor.
     * @param Client $apiClient
     * @param User $currentUser
     * @param SettingsHelper $settingsHelper
     * @param SidebarEntityService $sidebarEntityService
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        Client $apiClient,
        User $currentUser,
        SettingsHelper $settingsHelper,
        SidebarEntityService $sidebarEntityService,
        UrlHelper $urlHelper
    ) {
        $this->apiClient = $apiClient;
        $this->currentUser = $currentUser;
        $this->settingsHelper = $settingsHelper;
        $this->sidebarEntityService = $sidebarEntityService;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Handles the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $settings = $request->getParsedBody();

        if (in_array($settings['locale'] ?? '', $this->settingsHelper->getLocales())) {
            $this->currentUser->setLocale($settings['locale']);
        } else {
            $this->currentUser->setLocale(Config::DEFAULT_LOCALE);
        }
        if (in_array($settings['recipeMode'] ?? '', $this->settingsHelper->getRecipeModes())) {
            $this->currentUser->setRecipeMode($settings['recipeMode']);
        } else {
            $this->currentUser->setRecipeMode(Config::DEFAULT_RECIPE_MODE);
        }

        $this->apiClient->setLocale($this->currentUser->getLocale());
        $this->sidebarEntityService->refresh();
        return new RedirectResponse($this->urlHelper->generate(RouteNames::SETTINGS));
    }
}