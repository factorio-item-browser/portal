<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Recipe;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Exception\ApiClientException;
use FactorioItemBrowser\Api\Client\Request\Recipe\RecipeMachinesRequest;
use FactorioItemBrowser\Api\Client\Response\Recipe\RecipeMachinesResponse;
use FactorioItemBrowser\Portal\Constant\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * The request handler of the recipe machine pages.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeMachinePageHandler implements RequestHandlerInterface
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
        $name = rawurldecode($request->getAttribute('name'));
        $page = intval($request->getAttribute('page'));

        $machineRequest = new RecipeMachinesRequest();
        $machineRequest->setName($name)
                       ->setNumberOfResults(Config::MACHINE_PER_PAGE)
                       ->setIndexOfFirstResult(($page - 1) * Config::MACHINE_PER_PAGE);

        try {
            /* @var RecipeMachinesResponse $machineResponse */
            $machineResponse = $this->apiClient->send($machineRequest);

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
            $response = new JsonResponse([]);
        }
        return $response;
    }
}