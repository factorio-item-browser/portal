<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Entity\GenericEntityWithRecipes;
use FactorioItemBrowser\Api\Client\Request\Item\ItemProductRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemProductResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of the item tooltips.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemTooltipHandler implements RequestHandlerInterface
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
    public function __construct(Client $apiClient, TemplateRendererInterface $templateRenderer)
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