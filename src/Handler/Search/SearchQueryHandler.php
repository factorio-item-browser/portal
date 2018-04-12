<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Search;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Search\SearchQueryRequest;
use FactorioItemBrowser\Api\Client\Response\Search\SearchQueryResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler for the search queries.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SearchQueryHandler implements RequestHandlerInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The layout params helper.
     * @var LayoutParamsHelper
     */
    protected $layoutParamsHelper;

    /**
     * The template renderer.
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * Initializes the request handler.
     * @param Client $apiClient
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(
        Client $apiClient,
        LayoutParamsHelper $layoutParamsHelper,
        TemplateRendererInterface $templateRenderer
    )
    {
        $this->apiClient = $apiClient;
        $this->layoutParamsHelper = $layoutParamsHelper;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getAttribute('query');
        $this->layoutParamsHelper->setSearchQuery($query);

        $searchRequest = new SearchQueryRequest();
        $searchRequest->setQuery($query)
                      ->setNumberOfResults(Config::SEARCH_RESULTS_PER_PAGE)
                      ->setIndexOfFirstResult(0)
                      ->setNumberOfRecipesPerResult(Config::SEARCH_RECIPE_COUNT);

        /* @var SearchQueryResponse $searchResponse */
        $searchResponse = $this->apiClient->send($searchRequest);

        return new HtmlResponse($this->templateRenderer->render('portal::search/query', [
            'query' => $query,
            'results' => $searchResponse->getResults(),
            'totalNumberOfResults' => $searchResponse->getTotalNumberOfResults(),
            'currentPage' => 1
        ]));
    }
}