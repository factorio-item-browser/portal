<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Search;

use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Api\Client\Request\Search\SearchQueryRequest;
use FactorioItemBrowser\Api\Client\Response\Search\SearchQueryResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler for the search queries.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SearchQueryHandler extends AbstractRenderHandler
{
    /**
     * The layout params helper.
     * @var LayoutParamsHelper
     */
    protected $layoutParamsHelper;

    /**
     * The URL helper.
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Initializes the request handler.
     * @param ApiClientInterface $apiClient
     * @param LayoutParamsHelper $layoutParamsHelper
     * @param TemplateRendererInterface $templateRenderer
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        ApiClientInterface $apiClient,
        LayoutParamsHelper $layoutParamsHelper,
        TemplateRendererInterface $templateRenderer,
        UrlHelper $urlHelper
    ) {
        parent::__construct($apiClient, $templateRenderer);
        $this->layoutParamsHelper = $layoutParamsHelper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = rawurldecode($request->getAttribute('query'));
        if (strlen($query) === 0) {
            $query = $request->getQueryParams()['query'] ?? '';
            if (strlen($query) > 0) {
                $response = new RedirectResponse($this->urlHelper->generate(RouteNames::SEARCH_QUERY, [
                    'query' => $query
                ]));
            } else {
                $response = new RedirectResponse($this->urlHelper->generate(RouteNames::INDEX));
            }
        } else {
            $this->layoutParamsHelper->setSearchQuery($query);

            $searchRequest = new SearchQueryRequest();
            $searchRequest->setQuery($query)
                ->setNumberOfResults(Config::SEARCH_RESULTS_PER_PAGE)
                ->setIndexOfFirstResult(0)
                ->setNumberOfRecipesPerResult(Config::SEARCH_RECIPE_COUNT);

            /* @var SearchQueryResponse $searchResponse */
            $searchResponse = $this->apiClient->fetchResponse($searchRequest);

            $response = new HtmlResponse($this->templateRenderer->render('portal::search/query', [
                'query' => $query,
                'results' => $searchResponse->getResults(),
                'totalNumberOfResults' => $searchResponse->getTotalNumberOfResults(),
                'currentPage' => 1
            ]));
        }
        return $response;
    }
}
