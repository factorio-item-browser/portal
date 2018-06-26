<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Index;

use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Item\ItemRandomRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemRandomResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * The class handling the index page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class IndexHandler extends AbstractRenderHandler
{
    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ApiClientException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $randomItemsRequest = new ItemRandomRequest();
        $randomItemsRequest->setNumberOfResults(Config::INDEX_RANDOM_ITEMS)
                           ->setNumberOfRecipesPerResult(Config::SEARCH_RECIPE_COUNT);

        /* @var ItemRandomResponse $randomItemsResponse */
        $randomItemsResponse = $this->apiClient->send($randomItemsRequest);

        return new HtmlResponse($this->templateRenderer->render('portal::index/index', [
            'randomItems' => $randomItemsResponse->getItems()
        ]));
    }
}