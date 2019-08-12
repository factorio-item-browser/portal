<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Item\ItemProductRequest;
use FactorioItemBrowser\Api\Client\Response\Item\ItemProductResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler of the item tooltips.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemTooltipHandler extends AbstractRenderHandler
{
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
                       ->setNumberOfResults(Config::TOOLTIP_RECIPES);

        try {
            /** @var ItemProductResponse $productResponse */
            $productResponse = $this->apiClient->fetchResponse($productRequest);

            $response = new JsonResponse([
                'content' => $this->templateRenderer->render('portal::item/tooltip', [
                    'item' => $productResponse->getItem(),
                    'layout' => false
                ])
            ]);
        } catch (ApiClientException $e) {
            $response = new JsonResponse([]);
        }
        return $response;
    }
}
