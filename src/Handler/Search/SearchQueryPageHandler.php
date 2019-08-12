<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Search;

use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Search\SearchQueryRequest;
use FactorioItemBrowser\Api\Client\Response\Search\SearchQueryResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler of additional pages to a search.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SearchQueryPageHandler extends AbstractRenderHandler
{
    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = rawurldecode($request->getAttribute('query'));
        $page = intval($request->getAttribute('page'));

        if (strlen($query) === 0) {
            $response = new JsonResponse([]);
        } else {
            $searchRequest = new SearchQueryRequest();
            $searchRequest->setQuery($query)
                          ->setNumberOfResults(Config::SEARCH_RESULTS_PER_PAGE)
                          ->setIndexOfFirstResult(($page - 1) * Config::SEARCH_RESULTS_PER_PAGE)
                          ->setNumberOfRecipesPerResult(Config::SEARCH_RECIPE_COUNT);

            try {
                /** @var SearchQueryResponse $searchResponse */
                $searchResponse = $this->apiClient->fetchResponse($searchRequest);

                $response = new JsonResponse([
                    'content' => $this->templateRenderer->render('portal::search/page', [
                        'query' => $query,
                        'results' => $searchResponse->getResults(),
                        'totalNumberOfResults' => $searchResponse->getTotalNumberOfResults(),
                        'currentPage' => $page,
                        'layout' => false
                    ])
                ]);
            } catch (ApiClientException $e) {
                $response = $this->renderPaginatedListError();
            }
        }
        return $response;
    }
}
