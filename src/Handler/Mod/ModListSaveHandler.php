<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Mod\ModMetaRequest;
use FactorioItemBrowser\Api\Client\Response\Mod\ModMetaResponse;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;

/**
 * The request handler for saving the list of enabled mods.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ModListSaveHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The current user.
     * @var User
     */
    protected $currentUser;

    /**
     * The meta session container.
     * @var MetaSessionContainer
     */
    protected $metaSessionContainer;

    /**
     * The URL helper.
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param User $currentUser
     * @param MetaSessionContainer $metaSessionContainer
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        Client $apiClient,
        User $currentUser,
        MetaSessionContainer $metaSessionContainer,
        UrlHelper $urlHelper
    )
    {
        $this->apiClient = $apiClient;
        $this->currentUser = $currentUser;
        $this->metaSessionContainer = $metaSessionContainer;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $enabledMods = $body['enabledMods'] ?? [];

        $this->currentUser->setEnabledModNames($enabledMods);
        $this->apiClient->setEnabledModNames($enabledMods)
                        ->clearAuthorizationToken();

        /* @var ModMetaResponse $modMetaResponse */
        $modMetaResponse = $this->apiClient->send(new ModMetaRequest());
        $this->metaSessionContainer->setNumberOfAvailableMods($modMetaResponse->getNumberOfAvailableMods())
                                   ->setNumberOfEnabledMods($modMetaResponse->getNumberOfEnabledMods());

        // @todo Check sidebar entities

        return new RedirectResponse($this->urlHelper->generate(RouteNames::MOD_LIST));
    }
}