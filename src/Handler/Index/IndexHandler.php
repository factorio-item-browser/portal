<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Index;

use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Item\ItemRandomRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemRandomResponse;
use FactorioItemBrowser\Portal\Handler\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * The class handling the index page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class IndexHandler extends AbstractRequestHandler
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
        $randomItemsRequest->setNumberOfResults(12)
                           ->setNumberOfRecipesPerResult(3);

        /* @var ItemRandomResponse $randomItemsResponse */
        $randomItemsResponse = $this->apiClient->send($randomItemsRequest);

        return new HtmlResponse($this->templateRenderer->render('portal::index/index', [
            'randomItems' => $randomItemsResponse->getItems()
        ]));
    }
}