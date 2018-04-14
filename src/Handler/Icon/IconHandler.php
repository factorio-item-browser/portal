<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Icon;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Generic\GenericIconRequest;
use FactorioItemBrowser\Api\Client\Response\Generic\GenericIconResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler providing the icons of entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class IconHandler implements RequestHandlerInterface
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
        $icons = [];
        $processedEntities = [];

        $entities = $request->getParsedBody()['entities'] ?? [];
        if (is_array($entities) && count($entities) > 0) {
            $iconRequest = new GenericIconRequest();
            foreach ($entities as $entity) {
                $parts = explode('/', $entity, 2);
                if (count($parts) === 2 && strlen($parts[0]) > 0 && strlen($parts[1]) > 0) {
                    $iconRequest->addEntity($parts[0], $parts[1]);
                }
            }

            /* @var GenericIconResponse $iconResponse */
            $iconResponse = $this->apiClient->send($iconRequest);
            $icons = $iconResponse->getIcons();

            $processedEntities = [];
            foreach ($icons as $icon) {
                foreach ($icon->getEntities() as $entity) {
                    $processedEntities[] = $entity->getType() . '/' . $entity->getName();
                }
            }
        }

        return new JsonResponse([
            'processedEntities' => $processedEntities,
            'content' => $this->templateRenderer->render('portal::icon/style', [
                'icons' => $icons,
                'layout' => false
            ]),
        ]);
    }
}