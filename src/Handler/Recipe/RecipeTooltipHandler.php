<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Entity\GenericEntityWithRecipes;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeDetailsRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeDetailsResponse;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler of the recipe tooltips.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeTooltipHandler extends AbstractRenderHandler
{
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
            /** @var RecipeDetailsResponse $detailsResponse */
            $detailsResponse = $this->apiClient->fetchResponse($detailsRequest);

            if (count($detailsResponse->getRecipes()) > 0) {
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
                    'content' => $this->templateRenderer->render('portal::recipe/tooltip', [
                        'entity' => $entity,
                        'layout' => false
                    ])
                ]);
            } else {
                $response = new JsonResponse([]);
            }
        } catch (ApiClientException $e) {
            $response = new JsonResponse([]);
        }
        return $response;
    }
}
