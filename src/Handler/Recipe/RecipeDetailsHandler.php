<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\Recipe;
use FactorioItemBrowser\Api\Client\Exception\NotFoundException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeDetailsRequest;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeMachinesRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeDetailsResponse;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeMachinesResponse;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler rendering the details of a recipe.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeDetailsHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The sidebar entity database service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param SidebarEntityService $sidebarEntityService
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        Client $apiClient,
        SidebarEntityService $sidebarEntityService,
        TemplateRendererInterface $templateRenderer
    )
    {
        $this->apiClient = $apiClient;
        $this->sidebarEntityService = $sidebarEntityService;
        $this->templateRenderer = $templateRenderer;
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
        $detailsRequest->setNames([$name]);
        $machinesRequest = new RecipeMachinesRequest();
        $machinesRequest->setName($name)
                        ->setNumberOfResults(144);

        try {
            /* @var RecipeDetailsResponse $detailsResponse */
            $detailsResponse = $this->apiClient->send($detailsRequest);
            /* @var RecipeMachinesResponse $machinesResponse */
            $machinesResponse = $this->apiClient->send($machinesRequest);

            $recipes = [];
            foreach ($detailsResponse->getRecipes() as $recipe) {
                if (count($recipes) === 0) {
                    $this->sidebarEntityService->add($recipe);
                }

                $recipes[] = $recipe;
            }

            if (count($recipes) === 0) {
                $response = new HtmlResponse($this->templateRenderer->render('error::404'));
                $response = $response->withStatus(404);
            } else {
                usort($recipes, function (Recipe $left, Recipe $right): int {
                    return $right->getMode() <=> $left->getMode();
                });

                $response = new HtmlResponse($this->templateRenderer->render('portal::recipe/details', [
                    'machines' => $machinesResponse->getMachines(),
                    'recipes' => $recipes,
                    'totalNumberOfMachines' => $machinesResponse->getTotalNumberOfResults()
                ]));
            }
        } catch (NotFoundException $e) {
            $response = new HtmlResponse($this->templateRenderer->render('error::404'), 404);
        }
        return $response;
    }
}