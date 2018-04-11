<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Entity\GenericEntityWithRecipes;
use FactorioItemBrowser\Api\Client\Request\Item\ItemProductRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemProductResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Handler\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler of the item tooltips.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemTooltipHandler extends AbstractRequestHandler
{
    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $type = $request->getAttribute('type');
        $name = $request->getAttribute('name');

        $productRequest = new ItemProductRequest();
        $productRequest->setType($type)
                       ->setName($name)
                       ->setNumberOfResults(Config::TOOLTIP_RECIPES);

        /* @var ItemProductResponse $productResponse */
        $productResponse = $this->apiClient->send($productRequest);

        $entity = new GenericEntityWithRecipes();
        $entity->setType($productResponse->getItem()->getType())
               ->setName($productResponse->getItem()->getName())
               ->setLabel($productResponse->getItem()->getLabel())
               ->setDescription($productResponse->getItem()->getDescription())
               ->setTotalNumberOfRecipes($productResponse->getTotalNumberOfResults());

        foreach ($productResponse->getGroupedRecipes() as $groupedRecipe) {
            foreach ($groupedRecipe->getRecipes() as $recipe) {
                $entity->addRecipe($recipe);
            }
        }

        return new JsonResponse([
            'content' => $this->templateRenderer->render('portal::item/tooltip', [
                'entity' => $entity,
                'layout' => false
            ])
        ]);
    }
}