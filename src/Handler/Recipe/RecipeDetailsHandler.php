<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Api\Client\Entity\Recipe;
use FactorioItemBrowser\Api\Client\Exception\NotFoundException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeDetailsRequest;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeMachinesRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeDetailsResponse;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeMachinesResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler rendering the details of a recipe.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeDetailsHandler extends AbstractRenderHandler
{
    /**
     * The sidebar entity database service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * Initializes the request handler.
     * @param ApiClientInterface $apiClient
     * @param SidebarEntityService $sidebarEntityService
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        ApiClientInterface $apiClient,
        SidebarEntityService $sidebarEntityService,
        TemplateRendererInterface $templateRenderer
    ) {
        parent::__construct($apiClient, $templateRenderer);
        $this->sidebarEntityService = $sidebarEntityService;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = rawurldecode($request->getAttribute('name'));

        $detailsRequest = new RecipeDetailsRequest();
        $detailsRequest->addName($name);
        $machinesRequest = new RecipeMachinesRequest();
        $machinesRequest->setName($name)
                        ->setNumberOfResults(Config::MACHINE_PER_PAGE);

        try {
            $this->apiClient->sendRequest($detailsRequest);
            $this->apiClient->sendRequest($machinesRequest);

            /* @var RecipeDetailsResponse $detailsResponse */
            $detailsResponse = $this->apiClient->fetchResponse($detailsRequest);
            /* @var RecipeMachinesResponse $machinesResponse */
            $machinesResponse = $this->apiClient->fetchResponse($machinesRequest);

            $recipe = reset($detailsResponse->getRecipes()) ?: null;
            if ($recipe instanceof Recipe) {
                $this->sidebarEntityService->add($recipe);

                $response = new HtmlResponse($this->templateRenderer->render('portal::recipe/details', [
                    'machines' => $machinesResponse->getMachines(),
                    'recipe' => $recipe,
                    'totalNumberOfMachines' => $machinesResponse->getTotalNumberOfResults()
                ]));
            } else {
                $response = $this->renderNotFoundPage();
            }
        } catch (NotFoundException $e) {
            $response = $this->renderNotFoundPage();
        }
        return $response;
    }
}
