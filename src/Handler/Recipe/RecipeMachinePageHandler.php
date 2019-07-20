<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeMachinesRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeMachinesResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use FactorioItemBrowser\Portal\Handler\AbstractRenderHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler of the recipe machine pages.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeMachinePageHandler extends AbstractRenderHandler
{
    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = rawurldecode($request->getAttribute('name'));
        $page = intval($request->getAttribute('page'));

        $machineRequest = new RecipeMachinesRequest();
        $machineRequest->setName($name)
                       ->setNumberOfResults(Config::MACHINE_PER_PAGE)
                       ->setIndexOfFirstResult(($page - 1) * Config::MACHINE_PER_PAGE);

        try {
            /* @var RecipeMachinesResponse $machineResponse */
            $machineResponse = $this->apiClient->fetchResponse($machineRequest);

            $response = new JsonResponse([
                'content' => $this->templateRenderer->render('portal::recipe/machinePage', [
                    'currentPage' => $page,
                    'machines' => $machineResponse->getMachines(),
                    'recipeName' => $name,
                    'totalNumberOfMachines' => $machineResponse->getTotalNumberOfResults(),
                    'layout' => false
                ])
            ]);
        } catch (ApiClientException $e) {
            $response = $this->renderPaginatedListError();
        }
        return $response;
    }
}
