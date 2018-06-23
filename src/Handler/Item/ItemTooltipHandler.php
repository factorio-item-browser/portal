<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
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
        $type = rawurldecode($request->getAttribute('type'));
        $name = rawurldecode($request->getAttribute('name'));

        $productRequest = new ItemProductRequest();
        $productRequest->setType($type)
                       ->setName($name)
                       ->setNumberOfResults(Config::TOOLTIP_RECIPES);

        try {
            /* @var ItemProductResponse $productResponse */
            $productResponse = $this->apiClient->send($productRequest);

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