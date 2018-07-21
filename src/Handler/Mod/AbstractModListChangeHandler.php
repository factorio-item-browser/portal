<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\Mod;
use FactorioItemBrowser\Api\Client\Request\Mod\ModListRequest;
use FactorioItemBrowser\Api\Client\Request\Mod\ModMetaRequest;
use FactorioItemBrowser\Api\Client\Response\Mod\ModListResponse;
use FactorioItemBrowser\Api\Client\Response\Mod\ModMetaResponse;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use FactorioItemBrowser\Portal\Session\Container\ModListSessionContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;

/**
 * The abstract class of the request handlers changing the list of enabled mods.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractModListChangeHandler implements RequestHandlerInterface
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
     * The mod list session container.
     * @var ModListSessionContainer
     */
    protected $modListSessionContainer;

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
     * Initializes the request handler.
     * @param Client $apiClient
     * @param User $currentUser
     * @param MetaSessionContainer $metaSessionContainer
     * @param ModListSessionContainer $modListSessionContainer
     * @param SidebarEntityService $sidebarEntityService
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        Client $apiClient,
        User $currentUser,
        MetaSessionContainer $metaSessionContainer,
        ModListSessionContainer $modListSessionContainer,
        SidebarEntityService $sidebarEntityService,
        UrlHelper $urlHelper
    ) {
        $this->apiClient = $apiClient;
        $this->currentUser = $currentUser;
        $this->metaSessionContainer = $metaSessionContainer;
        $this->modListSessionContainer = $modListSessionContainer;
        $this->sidebarEntityService = $sidebarEntityService;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $enabledModNames = $this->getEnabledModNamesFromRequest($request);
        if (count($enabledModNames) > 0) {
            $this->currentUser->setEnabledModNames($enabledModNames);
            $this->apiClient->setEnabledModNames($enabledModNames)
                            ->clearAuthorizationToken();

            /* @var ModMetaResponse $modMetaResponse */
            $modMetaResponse = $this->apiClient->send(new ModMetaRequest());
            /* @var ModListResponse $modListResponse */
            $modListResponse = $this->apiClient->send(new ModListRequest());
            $this->sidebarEntityService->refresh();

            $this->metaSessionContainer->setNumberOfAvailableMods($modMetaResponse->getNumberOfAvailableMods())
                                       ->setNumberOfEnabledMods($modMetaResponse->getNumberOfEnabledMods());

            $missingModNames = $this->getMissingModNames($modListResponse->getMods(), $enabledModNames);

            $this->modListSessionContainer->setShowSuccessMessage(true)
                                          ->setMissingModNames($missingModNames);
        }
        return new RedirectResponse($this->urlHelper->generate(RouteNames::MOD_LIST));
    }

    /**
     * Returns the list of enabled mods from the request.
     * @param ServerRequestInterface $request
     * @return array|string[]
     */
    abstract protected function getEnabledModNamesFromRequest(ServerRequestInterface $request): array;

    /**
     * @param array|Mod[] $mods
     * @param array|string[] $enabledModNames
     * @return array|string[]
     */
    protected function getMissingModNames(array $mods, array $enabledModNames): array
    {
        $availableModNames = [];
        foreach ($mods as $mod) {
            $availableModNames[] = $mod->getName();
        }

        return array_diff($enabledModNames, $availableModNames);
    }
}
