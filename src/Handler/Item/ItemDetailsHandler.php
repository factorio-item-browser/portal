<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Exception\NotFoundException;
use FactorioItemBrowser\Api\Client\Request\Item\ItemIngredientRequest;
use FactorioItemBrowser\Api\Client\Request\Item\ItemProductRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemIngredientResponse;
use FactorioItemBrowser\Api\Client\Response\Item\ItemProductResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of the item page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemDetailsHandler implements RequestHandlerInterface
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
        $type = rawurldecode($request->getAttribute('type'));
        $name = rawurldecode($request->getAttribute('name'));

        $productRequest = new ItemProductRequest();
        $productRequest->setType($type)
                       ->setName($name)
                       ->setNumberOfResults(Config::ITEM_RECIPE_PER_PAGE);

        /* @var ItemProductResponse $productResponse */
        $productResponse = $this->apiClient->send($productRequest);

        $ingredientRequest = new ItemIngredientRequest();
        $ingredientRequest->setType($type)
                          ->setName($name)
                          ->setNumberOfResults(Config::ITEM_RECIPE_PER_PAGE);
        
        /* @var ItemIngredientResponse $ingredientResponse */
        $ingredientResponse = $this->apiClient->send($ingredientRequest);

        try {
            $this->sidebarEntityService->add($productResponse->getItem());
            $response = new HtmlResponse($this->templateRenderer->render('portal::item/details', [
                'itemWithIngredients' => $ingredientResponse->getItem(),
                'itemWithProducts' => $productResponse->getItem(),
            ]));
        } catch (NotFoundException $e) {
            $response = new HtmlResponse($this->templateRenderer->render('error::404'), 404);
        }
        return $response;
    }
}