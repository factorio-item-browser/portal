<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Request\Item\ItemIngredientRequest;
use FactorioItemBrowser\Api\Client\Request\Item\ItemProductRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemIngredientResponse;
use FactorioItemBrowser\Api\Client\Response\Item\ItemProductResponse;
use FactorioItemBrowser\Portal\Handler\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * The request handler of the item page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemDetailsHandler extends AbstractRequestHandler
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
            ->setNumberOfResults(12);

        /* @var ItemProductResponse $productResponse */
        $productResponse = $this->apiClient->send($productRequest);

        $ingredientRequest = new ItemIngredientRequest();
        $ingredientRequest->setType($type)
                          ->setName($name)
                          ->setNumberOfResults(12);
        
        /* @var ItemIngredientResponse $ingredientResponse */
        $ingredientResponse = $this->apiClient->send($ingredientRequest);

        return new HtmlResponse($this->templateRenderer->render('portal::item/details', [
            'item' => $productResponse->getItem(),
            'productRecipes' => $productResponse->getGroupedRecipes(),
            'totalNumberOfProductRecipes' => $productResponse->getTotalNumberOfResults(),
            'ingredientRecipes' => $ingredientResponse->getGroupedRecipes(),
            'totalNumberOfIngredientRecipes' => $ingredientResponse->getTotalNumberOfResults(),
        ]));
    }
}