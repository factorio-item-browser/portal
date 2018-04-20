<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\GenericEntityWithRecipes;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeDetailsRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeDetailsResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of the recipe tooltips.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeTooltipHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        Client $apiClient,
        TemplateRendererInterface $templateRenderer
    )
    {
        $this->apiClient = $apiClient;
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

        try {
            /* @var RecipeDetailsResponse $detailsResponse */
            $detailsResponse = $this->apiClient->send($detailsRequest);

            $entity = new GenericEntityWithRecipes();
            foreach ($detailsResponse->getRecipes() as $recipe) {
                if (count($entity->getRecipes()) === 0) {
                    $entity->setType($recipe->getType())
                           ->setName($recipe->getName())
                           ->setLabel($recipe->getLabel())
                           ->setDescription($recipe->getDescription());
                }
                $entity->addRecipe($recipe);
            }

            $response = new JsonResponse([
                'content' => $this->templateRenderer->render('portal::item/tooltip', [
                    'entity' => $entity,
                    'layout' => false
                ])
            ]);
        } catch (ApiClientException $e) {
            $response = new JsonResponse([]);
        }
        return $response;
    }
}