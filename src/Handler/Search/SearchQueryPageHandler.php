<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Search;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Search\SearchQueryRequest;
use FactorioItemBrowser\Api\Client\Response\Search\SearchQueryResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of additional pages to a search.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SearchQueryPageHandler implements RequestHandlerInterface
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
    public function __construct(
        Client $apiClient,
        TemplateRendererInterface $templateRenderer
    )
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
        $query = rawurldecode($request->getAttribute('query'));
        $page = intval($request->getAttribute('page'));

        $searchRequest = new SearchQueryRequest();
        $searchRequest->setQuery($query)
                      ->setNumberOfResults(Config::SEARCH_RESULTS_PER_PAGE)
                      ->setIndexOfFirstResult(($page - 1) * Config::SEARCH_RESULTS_PER_PAGE)
                      ->setNumberOfRecipesPerResult(Config::SEARCH_RECIPE_COUNT);

        /* @var SearchQueryResponse $searchResponse */
        $searchResponse = $this->apiClient->send($searchRequest);

        return new JsonResponse([
            'content' => $this->templateRenderer->render('portal::search/page', [
                'query' => $query,
                'results' => $searchResponse->getResults(),
                'totalNumberOfResults' => $searchResponse->getTotalNumberOfResults(),
                'currentPage' => $page,
                'layout' => false
            ])
        ]);
    }
}